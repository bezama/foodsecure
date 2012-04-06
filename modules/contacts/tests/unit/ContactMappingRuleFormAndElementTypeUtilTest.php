<?php


    class ContactMappingRuleFormAndElementTypeUtilTest extends ImportBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function testMakeCollectionByAttributeImportRules()
        {
            //Contact
            $attributeImportRules = new ContactAttributeImportRules(new ImportModelTestItem(), 'hasOne');
            $collection           = MappingRuleFormAndElementTypeUtil::
                                    makeCollectionByAttributeImportRules($attributeImportRules,
                                                                         'hasOne', 'importColumn');
            $this->assertEquals(2, count($collection));
            $this->assertEquals('ImportMappingRuleDefaultModelNameId', $collection[0]['elementType']);
            $this->assertEquals('DefaultModelNameIdMappingRuleForm', get_class($collection[0]['mappingRuleForm']));
            $this->assertEquals('ImportMappingModelIdValueTypeDropDown', $collection[1]['elementType']);
            $this->assertEquals('IdValueTypeMappingRuleForm', get_class($collection[1]['mappingRuleForm']));

            //Contact Derived
            $attributeImportRules = new ContactDerivedAttributeImportRules(new ImportModelTestItem(), 'contactDerived');
            $collection           = MappingRuleFormAndElementTypeUtil::
                                    makeCollectionByAttributeImportRules($attributeImportRules,
                                                                         'contactDerived', 'importColumn');
            $this->assertEquals(2, count($collection));
            $this->assertEquals('ImportMappingRuleDefaultModelNameId', $collection[0]['elementType']);
            $this->assertEquals('DefaultModelNameIdDerivedAttributeMappingRuleForm', get_class($collection[0]['mappingRuleForm']));
            $this->assertEquals('ImportMappingModelIdValueTypeDropDown', $collection[1]['elementType']);
            $this->assertEquals('IdValueTypeMappingRuleForm', get_class($collection[1]['mappingRuleForm']));

            //Contact State
            ContactsModule::loadStartingData();
            $attributeImportRules = new ContactStateAttributeImportRules(new ImportModelTestItem(), 'state');
            $collection           = MappingRuleFormAndElementTypeUtil::
                                    makeCollectionByAttributeImportRules($attributeImportRules,
                                                                         'state', 'importColumn');
            $this->assertEquals(1, count($collection));
            $this->assertEquals('ImportMappingRuleContactStatesDropDown', $collection[0]['elementType']);
            $this->assertEquals('DefaultContactStateIdMappingRuleForm', get_class($collection[0]['mappingRuleForm']));
        }
    }
?>