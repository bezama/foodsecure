<?php


    /**
     * Filtered lists that are specific to the Accounts module.
     */
    class AccountsFilteredList extends FilteredList
    {
        protected static function getLabel()
        {
            return 'Filtered AccountsModuleSingularLabel List';
        }

        protected static function getPluralLabel()
        {
            return 'Filtered AccountsModuleSingularLabel Lists';
        }

        public static function isTypeDeletable()
        {
            return true;
        }
    }
?>