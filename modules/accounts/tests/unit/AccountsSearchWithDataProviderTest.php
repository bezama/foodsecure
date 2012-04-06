<?php


    /**
     * Tests on accounts specific search permutations.
     */
    class AccountsSearchWithDataProviderTest extends fosaDataProviderBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
        }

        public function testSearchByCustomField()
        {
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            //todo: add accounts. 2 of them.
            AccountTestHelper::createAccountByNameForOwner('aFirstAccount', $super);
            AccountTestHelper::createAccountByNameForOwner('aSecondAccount', $super);

            //Searching with a custom field that is not blank should not produce any errors.
            //The data returned should be no accounts.
            $fakePostData        = array('name'         => null,
                                         'officePhone'  => null,
                                         'industry'     => array('value' => 'Banking'),
                                         'officeFax'    => null);
            $account             = new Account(false);
            $searchForm          = new AccountsSearchForm($account);
            $metadataAdapter     = new SearchDataProviderMetadataAdapter($searchForm, $super->id, $fakePostData);
            $searchAttributeData = $metadataAdapter->getAdaptedMetadata();

            //Run search and make sure the data returned matches how many total accounts are available.
            $dataProvider        = new RedBeanModelDataProvider('Account', null, false, $searchAttributeData);
            $data                = $dataProvider->getData();
            $this->assertEquals(0, count($data));
        }

        /**
         * @depends testSearchByCustomField
         */
        public function testSearchMemberOfAndMembers()
        {
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            //Test member of search.
            $_FAKEPOST['Account'] = array();
            $_FAKEPOST['Account']['account']['id'] = '4';
            $metadataAdapter = new SearchDataProviderMetadataAdapter(
                new Account(false),
                1,
                $_FAKEPOST['Account']
            );
            $searchAttributeData = $metadataAdapter->getAdaptedMetadata();
            $joinTablesAdapter   = new RedBeanModelJoinTablesQueryAdapter('Account');
            $quote        = DatabaseCompatibilityUtil::getQuote();
            $where        = RedBeanModelDataProvider::makeWhere('Account', $searchAttributeData, $joinTablesAdapter);
            $compareWhere = "({$quote}account{$quote}.{$quote}account_id{$quote} = 4)";
            $this->assertEquals($compareWhere, $where);

            //Now test that the joinTablesAdapter has correct information.
            $this->assertEquals(0, $joinTablesAdapter->getFromTableJoinCount());
            $this->assertEquals(0, $joinTablesAdapter->getLeftTableJoinCount());
            $leftTables = $joinTablesAdapter->getLeftTablesAndAliases();

            //Make sure the sql runs properly.
            $dataProvider = new RedBeanModelDataProvider('Account', null, false, $searchAttributeData);
            $data = $dataProvider->getData();

            //Test accounts search.
            $_FAKEPOST['Account'] = array();
            $_FAKEPOST['Account']['accounts']['id'] = '5';
            $metadataAdapter     = new SearchDataProviderMetadataAdapter(new Account(false), $super->id, $_FAKEPOST['Account']);
            $searchAttributeData = $metadataAdapter->getAdaptedMetadata();
            $joinTablesAdapter   = new RedBeanModelJoinTablesQueryAdapter('Account');
            $quote        = DatabaseCompatibilityUtil::getQuote();
            $where        = RedBeanModelDataProvider::makeWhere('Account', $searchAttributeData, $joinTablesAdapter);
            $compareWhere = "({$quote}account1{$quote}.{$quote}id{$quote} = 5)";
            $this->assertEquals($compareWhere, $where);
            //Now test that the joinTablesAdapter has correct information.
            $this->assertEquals(0, $joinTablesAdapter->getFromTableJoinCount());
            $this->assertEquals(1, $joinTablesAdapter->getLeftTableJoinCount());
            $leftTables = $joinTablesAdapter->getLeftTablesAndAliases();
            $this->assertEquals('account', $leftTables[0]['tableName']);

            //Make sure the sql runs properly.
            $dataProvider = new RedBeanModelDataProvider('Account', null, false, $searchAttributeData);
            $data = $dataProvider->getData();
        }

        /**
         * @depends testSearchMemberOfAndMembers
         */
        public function testSearchByCustomFieldWithEscapedContent()
        {
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            //Searching with a custom field that is not blank should not produce any errors.
            //The data returned should be no accounts.
            $fakePostData        = array('name'         => null,
                                         'officePhone'  => null,
                                         'industry'     => array('value' => "Ban'king"),
                                         'officeFax'    => null);
            $account             = new Account(false);
            $searchForm          = new AccountsSearchForm($account);
            $metadataAdapter     = new SearchDataProviderMetadataAdapter($searchForm, $super->id, $fakePostData);
            $searchAttributeData = $metadataAdapter->getAdaptedMetadata();

            //Run search and make sure the data returned matches how many total accounts are available.
            $dataProvider        = new RedBeanModelDataProvider('Account', null, false, $searchAttributeData);
            $data                = $dataProvider->getData();
            $this->assertEquals(0, count($data));
        }
    }
?>