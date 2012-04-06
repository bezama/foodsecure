<?php


    /**
     * Import rules for the contact state attribute. This is used for the states that are the starting state or after.
     */
    class LeadStateAttributeImportRules extends ContactStateAttributeImportRules
    {
        protected static function getAllModelAttributeMappingRuleFormTypesAndElementTypes()
        {
            return array('DefaultLeadStateId' => 'ImportMappingRuleContactStatesDropDown');
        }

        public static function getSanitizerUtilTypesInProcessingOrder()
        {
            return array('LeadState', 'LeadStateRequired');
        }
    }
?>