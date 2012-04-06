<?php


    /**
     * Import rules for the contact state attribute. This is used for the states that are the starting state or after.
     */
    class ContactStateAttributeImportRules extends DerivedAttributeImportRules
    {
        protected static function getAllModelAttributeMappingRuleFormTypesAndElementTypes()
        {
            return array('DefaultContactStateId' => 'ImportMappingRuleContactStatesDropDown');
        }

        public function getDisplayLabel()
        {
            return Yii::t('Default', 'Status');
        }

        public function getRealModelAttributeNames()
        {
            return array('state');
        }

        public static function getSanitizerUtilTypesInProcessingOrder()
        {
            return array('ContactState', 'ContactStateRequired');
        }

        public function resolveValueForImport($value, $columnMappingData, ImportSanitizeResultsUtil $importSanitizeResultsUtil)
        {
            $attributeNames = $this->getRealModelAttributeNames();
            assert('count($attributeNames) == 1');
            assert('$attributeNames[0] == "state"');
            $modelClassName = $this->getModelClassName();
            $value          = ImportSanitizerUtil::
                              sanitizeValueBySanitizerTypes(static::getSanitizerUtilTypesInProcessingOrder(),
                              $modelClassName, null, $value, $columnMappingData, $importSanitizeResultsUtil);
            return array('state' => $value);
        }
    }
?>