<?php


    /**
     * Defines the import rules for importing into the leads module.
     */
    class LeadsImportRules extends ImportRules
    {
        public static function getModelClassName()
        {
            return 'Contact';
        }

        /**
         * Get the display label used to describe the import rules.
         * @return string
         */
        public static function getDisplayLabel()
        {
            return LeadsModule::getModuleLabelByTypeAndLanguage('Plural');
        }

        /**
         * Get the array of available derived attribute types that can be mapped when using these import rules.
         * @return array
         */
        public static function getDerivedAttributeTypes()
        {
            return array_merge(parent::getDerivedAttributeTypes(), array('LeadState', 'FullName'));
        }

        /**
         * Get the array of attributes that cannot be mapped when using these import rules.
         * @return array
         */
        public static function getNonImportableAttributeNames()
        {
            return array_merge(parent::getNonImportableAttributeNames(), array('state', 'account'));
        }
    }
?>