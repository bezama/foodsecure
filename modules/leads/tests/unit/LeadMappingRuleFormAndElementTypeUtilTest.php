<?php


    class LeadMappingRuleFormAndElementTypeUtilTest extends ImportBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function testMakeCollectionByAttributeImportRules()
        {
            //Leads
            ContactsModule::loadStartingData();
            $attributeImportRules = new LeadStateAttributeImportRules(new ImportModelTestItem(), 'state');
            $collection           = MappingRuleFormAndElementTypeUtil::
                                    makeCollectionByAttributeImportRules($attributeImportRules,
                                                                         'state', 'importColumn');
            $this->assertEquals(1, count($collection));
            $this->assertEquals('ImportMappingRuleContactStatesDropDown', $collection[0]['elementType']);
            $this->assertEquals('DefaultLeadStateIdMappingRuleForm', get_class($collection[0]['mappingRuleForm']));
        }
    }
?>