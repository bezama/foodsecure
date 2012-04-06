<?php


    /**
     * Filtered lists that are specific to the Contacts module.
     */
    class ContactsFilteredList extends FilteredList
    {
        protected static function getLabel()
        {
            return 'Filtered ContactsModuleSingularLabel List';
        }

        protected static function getPluralLabel()
        {
            return 'Filtered ContactsModuleSingularLabel Lists';
        }

        public static function isTypeDeletable()
        {
            return true;
        }
    }
?>