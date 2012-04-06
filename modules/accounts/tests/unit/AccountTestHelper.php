<?php


    class AccountTestHelper
    {
        public static function createAccountByNameForOwner($name, $owner)
        {
            $account = new Account();
            $account->name  = $name;
            $account->owner = $owner;
            $saved = $account->save();
            assert('$saved');
            return $account;
        }

        public static function createAccountByNameTypeAndIndustryForOwner($name, $type, $industry, $owner)
        {
            $account = new Account();
            $account->name  = $name;
            $account->industry->value   = $industry;
            $account->type->value       = $type;
            $account->owner = $owner;
            $saved = $account->save();
            assert('$saved');
            return $account;
        }

        public static function createAccountsForSearchWithDataProviderTests()
        {
        }
    }
?>