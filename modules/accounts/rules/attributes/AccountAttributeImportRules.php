<?php


    /**
     * Import rules for an attribute that is an account model.
     */
    class AccountAttributeImportRules extends ModelAttributeImportRules
    {
        protected static function getAllModelAttributeMappingRuleFormTypesAndElementTypes()
        {
            return array('DefaultModelNameId' => 'ImportMappingRuleDefaultModelNameId');
        }

        protected static function getImportColumnOnlyModelAttributeMappingRuleFormTypesAndElementTypes()
        {
            return array('RelatedModelValueType' => 'ImportMappingRelatedModelValueTypeDropDown');
        }

        public static function getSanitizerUtilTypesInProcessingOrder()
        {
            return array('RelatedModelNameOrIdValueType', 'ModelIdRequired');
        }
    }
?>