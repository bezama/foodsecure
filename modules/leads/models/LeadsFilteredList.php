<?php


    /**
     * Filtered lists that are specific to the Leads module.
     */
    class LeadsFilteredList extends FilteredList
    {
        protected static function getLabel()
        {
            return 'Filtered LeadsModuleSingularLabel List';
        }

        protected static function getPluralLabel()
        {
            return 'Filtered LeadsModuleSingularLabel Lists';
        }

        public static function isTypeDeletable()
        {
            return true;
        }
    }
?>