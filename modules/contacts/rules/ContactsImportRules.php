<?php


    /**
     * Defines the import rules for importing into the contacts module.
     */
    class ContactsImportRules extends ImportRules
    {
        public static function getModelClassName()
        {
            return 'Contact';
        }

            /**
         * Get the array of available derived attribute types that can be mapped when using these import rules.
         * @return array
         */
        public static function getDerivedAttributeTypes()
        {
            return array_merge(parent::getDerivedAttributeTypes(), array('ContactState', 'FullName'));
        }

        /**
         * Get the array of attributes that cannot be mapped when using these import rules.
         * @return array
         */
        public static function getNonImportableAttributeNames()
        {
            return array_merge(parent::getNonImportableAttributeNames(), array('state', 'companyName'));
        }
    }
?>