<?php


    class AccountReadPermissionsOptimizationBaseTest extends fosaBaseTest
    {
        protected function rebuildAndTestThatTheMungeDoesntChange()
        {
            $beforeRows = R::getAll('select munge_id, securableitem_id, count from account_read order by munge_id, securableitem_id, count');
            ReadPermissionsOptimizationUtil::rebuild();
            $afterRows  = R::getAll('select munge_id, securableitem_id, count from account_read order by munge_id, securableitem_id, count');

            if ($beforeRows != $afterRows)
            {
                echo "Before and after rebuild munge doesn't match.\n";
                echo "--------\n";
                foreach ($beforeRows as $row)
                {
                    echo join(', ', array_values($row)) . "\n";
                }
                echo "--------\n";
                foreach ($afterRows as $row)
                {
                    echo join(', ', array_values($row)) . "\n";
                }
                echo "--------\n";
            }

            $this->assertEquals(count($beforeRows), count($afterRows));
            $this->assertEquals($beforeRows, $afterRows);
        }

        protected function nukeExistingAccounts()
        {
            while (true)
            {
                $accounts = Account::getSubset(0, 50); // Nuke 50 at a time to
                if (count($accounts) == 0)             // avoid memory issues when
                {                                      // we get to the big numbers.
                    break;
                }
                foreach ($accounts as $account)
                {
                    $account->delete();
                    unset($account);
                }
                unset($accounts);
            }
            $this->assertEquals(0, Account::getCount());
        }

        protected function createAccounts($count, $testRebuildAfterCreateAccount = false, $firstAccount = 1, $step = 1)
        {
            $this->assertTrue(is_bool($testRebuildAfterCreateAccount));
            $this->assertTrue($firstAccount <= $count);
            $this->assertTrue($step         <  $count);
            $betty = User::getByUsername('betty');
            $benny = User::getByUsername('benny');
            $salesStaff = Group::getByName('Sales Staff');
            $countThatBennyCanRead = 0;
            $accountIdsThatBennyCanRead = array();
            $startTime = microtime(true);
            for ($i = 0; $i < $count; $i++)
            {
                $bennyCanRead = false;
                $account = self::createRandomAccount($i);
                $securableItemId = $i + 1;
                if ($i % 10 == 0)
                {
                    $account->owner = $betty;
                    $bennyCanRead = true; // Because he is betty's manager.
                }
                if ($i % 8 == 0)
                {
                    $account->addPermissions($benny,      Permission::READ);
                    $bennyCanRead = true; // Just coz.
                }
                if ($i % 12 == 0)
                {
                    $account->addPermissions($salesStaff, Permission::READ);
                    $bennyCanRead = true; // Benny is in Sales Staff.
                }

                $this->assertTrue($account->save());
                if ($bennyCanRead)
                {
                    $countThatBennyCanRead++;
                    if ($countThatBennyCanRead <= 10)
                    {
                        $accountIdsThatBennyCanRead[] = $account->id;
                    }
                }

                if ($i >= $firstAccount - 1                 &&
                    ($i - ($firstAccount - 1)) % $step == 0 &&
                    $testRebuildAfterCreateAccount)
                {
                    $startTime = microtime(true);
                    ReadPermissionsOptimizationUtil::rebuild(true);
                    $endTime = microtime(true);
                    if ($this->isDebug())
                    {
                        echo 'Rebuilt the munge in php in ' . round($endTime - $startTime, 1) . ' seconds, ' . self::getAccountMungeRowCount() . " rows.\n";
                    }
                    $phpRows = self::getAccountMungeRows();

                    // If $securityOptimized is false in debug.php the second one will just do the php again.
                    $startTime = microtime(true);
                    ReadPermissionsOptimizationUtil::rebuild();
                    $endTime = microtime(true);
                    if ($this->isDebug())
                    {
                        echo 'Rebuilt the munge ' . (SECURITY_OPTIMIZED ? 'optimized' : 'in php') . ' in ' . round($endTime - $startTime, 1) . ' seconds, ' . self::getAccountMungeRowCount() . " rows.\n";
                    }
                    $otherRows = self::getAccountMungeRows();
                    if ($phpRows != $otherRows)
                    {
                        echo 'PHP & optimized munges don\'t match after account ' . ($i + 1) . "\n";
                        echo "--------\n";
                        foreach ($phpRows as $row)
                        {
                            echo join(', ', array_values($row)) . "\n";
                        }
                        echo "--------\n";
                        foreach ($otherRows as $row)
                        {
                            echo join(', ', array_values($row)) . "\n";
                        }
                        echo "--------\n";
                    }
                    $this->assertEquals(count($phpRows), count($otherRows));
                    $this->assertEquals($phpRows, $otherRows);
                }
            }
            $endTime = microtime(true);

            return array($endTime - $startTime, $countThatBennyCanRead, $accountIdsThatBennyCanRead);
        }

        protected static function createRandomAccount($i)
        {
            $account = new Account();
            $account->name                       = "Account#$i";
            $account->officePhone                = rand(10000000, 90000000);
            $account->officeFax                  = rand(10000000, 90000000);
            $account->employees                  = rand(1, 100);
            $account->website                    = "http://www.account$i.com";
            $account->annualRevenue              = rand(10000, 10000000);
            $account->description                = "An account for some company called Account#$i.";
            $account->primaryEmail->emailAddress = "info@account$i.com";
            $account->primaryEmail->optOut       = false;
            $account->primaryEmail->isInvalid    = false;
            $account->billingAddress->street1    = "$i Some St";
            $account->billingAddress->city       = 'City';
            $account->billingAddress->state      = 'State';
            $account->billingAddress->postalCode = rand(1000, 9999);
            return $account;
        }

        protected static function accountMungeDoesntChangeWhenRebuilt()
        {
            //Need to forget all since sometimes the related information is cached from
            //before something occurred during a test.
            RedBeanModelsCache::forgetAll();
            RedBeansCache::forgetAll();
            $beforeRows = self::getAccountMungeRows();
            ReadPermissionsOptimizationUtil::rebuild();
            $afterRows  = self::getAccountMungeRows();

            if ($beforeRows != $afterRows)
            {
                echo "Before and after rebuild munge doesn't match.\n";
                self::echoRows($beforeRows);
                self::echoRows($afterRows);
            }

            return $beforeRows == $afterRows;
        }

        protected static function getAccountMungeRows(SecurableItem $securableItem = null)
        {
            if ($securableItem === null)
            {
                $rows = R::getAll('select   name, munge_id, count
                                   from     account_read, ownedsecurableitem, account
                                   where    account_read.securableitem_id = ownedsecurableitem.securableitem_id and
                                            ownedsecurableitem.id         = account.ownedsecurableitem_id
                                   order by name, munge_id, account_read.securableitem_id, count');
            }
            else
            {
                $securableItemId = $securableItem->getClassId('SecurableItem');
                $rows = R::getAll("select   munge_id, count
                                   from     account_read
                                   where    securableitem_id = $securableItemId
                                   order by munge_id, count");
            }
            $rowsWithValues = array();
            foreach ($rows as $row)
            {
                $row = array_values($row);
                array_walk($row, array('self', 'stripFullStops'));
                $rowsWithValues[] = $row;
            }
            return $rowsWithValues;
        }

        protected static function stripFullStops(&$value, $index)
        {
            // The names the accounts have . to pad them out
            // to the minimum length they require, which is 3.
            // This is to make them appear in the debug output
            // as they do in the munge scenarios powerpoint
            // slides.
            $value = str_replace('.', '', $value);
        }

        protected static function echoAccountMungeRows(SecurableItem $securableItem = null)
        {
            self::echoRows(self::getAccountMungeRows($securableItem));
        }

        protected static function echoRows(array $rows)
        {
            echo "--------\n";
            foreach ($rows as $row)
            {
                echo join(', ', array_values($row)) . "\n";
            }
            echo "--------\n";
        }

        protected static function getAccountMungeRowCount()
        {
            return intval(R::getCell('select count(*) from account_read'));
        }
    }
?>
