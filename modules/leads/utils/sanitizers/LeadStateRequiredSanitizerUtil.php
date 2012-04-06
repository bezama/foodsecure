<?php


    /**
     * Data analyzer for contact state values that are before the starting state. Manages if the value is empty
     * or null and resolves against if the state attribute is required.
     */
    class LeadStateRequiredSanitizerUtil extends ContactStateRequiredSanitizerUtil
    {
        public static function getLinkedMappingRuleType()
        {
            return 'DefaultLeadStateId';
        }
    }
?>