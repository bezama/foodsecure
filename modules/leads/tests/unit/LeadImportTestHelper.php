<?php


    class LeadImportTestHelper
    {
        public static function makeStateColumnMappingData($defaultValue = null)
        {
            assert('$defaultValue == null || is_int($defaultValue)');
            return array('attributeIndexOrDerivedType' => 'LeadState',
                         'type'                        => 'importColumn',
                         'mappingRulesData'            => array(
                             'DefaultLeadStateIdMappingRuleForm' =>
                             array('defaultStateId' => $defaultValue)));
        }
    }
?>