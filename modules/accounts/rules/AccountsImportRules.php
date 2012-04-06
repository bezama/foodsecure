<?php


    /**
     * Defines the import rules for importing into the accounts module.
     */
    class AccountsImportRules extends ImportRules
    {
        public static function getModelClassName()
        {
            return 'Account';
        }
    }
?>