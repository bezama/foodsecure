<?php


    class AccountMappingRuleFormAndElementTypeUtilTest extends ImportBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function testMakeCollectionByAttributeImportRules()
        {
            //Account
            $attributeImportRules = new AccountAttributeImportRules(new ImportModelTestItem(), 'hasOne');
            $collection           = MappingRuleFormAndElementTypeUtil::
                                    makeCollectionByAttributeImportRules($attributeImportRules,
                                                                         'hasOne', 'importColumn');
            $this->assertEquals(2, count($collection));
            $this->assertEquals('ImportMappingRuleDefaultModelNameId', $collection[0]['elementType']);
            $this->assertEquals('DefaultModelNameIdMappingRuleForm', get_class($collection[0]['mappingRuleForm']));
            $this->assertEquals('ImportMappingRelatedModelValueTypeDropDown', $collection[1]['elementType']);
            $this->assertEquals('RelatedModelValueTypeMappingRuleForm', get_class($collection[1]['mappingRuleForm']));

            //Account Derved
            $attributeImportRules = new AccountDerivedAttributeImportRules(new ImportModelTestItem(), 'accountDerived');
            $collection           = MappingRuleFormAndElementTypeUtil::
                                    makeCollectionByAttributeImportRules($attributeImportRules,
                                                                         'accountDerived', 'importColumn');
            $this->assertEquals(2, count($collection));
            $this->assertEquals('ImportMappingRuleDefaultModelNameId', $collection[0]['elementType']);
            $this->assertEquals('DefaultModelNameIdDerivedAttributeMappingRuleForm', get_class($collection[0]['mappingRuleForm']));
            $this->assertEquals('ImportMappingModelIdValueTypeDropDown', $collection[1]['elementType']);
            $this->assertEquals('IdValueTypeMappingRuleForm', get_class($collection[1]['mappingRuleForm']));
        }
    }
?>