<?php


    class ContactImportTestHelper
    {
        public static function makeStateColumnMappingData($defaultValue = null, $columnType = 'importColumn')
        {
            assert('$defaultValue == null || is_int($defaultValue)');
            return array('attributeIndexOrDerivedType' => 'ContactState',
                         'type'                        => $columnType,
                         'mappingRulesData'            => array(
                             'DefaultContactStateIdMappingRuleForm' =>
                             array('defaultStateId' => $defaultValue)));
        }
    }
?>