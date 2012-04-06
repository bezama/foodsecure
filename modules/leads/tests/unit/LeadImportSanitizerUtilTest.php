<?php


    class LeadImportSanitizerUtilTest extends ImportBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            ContactsModule::loadStartingData();
        }

        public function testSanitizeValueBySanitizerTypesForLeadStateTypeThatIsRequired()
        {
            $contactStates = ContactState::getAll();
            $this->assertEquals(6, count($contactStates));

            //Test a required contact state with no value or default value.
            $importSanitizeResultsUtil = new ImportSanitizeResultsUtil();
            $columnMappingData         = array('type' => 'importColumn', 'mappingRulesData' => array(
                                               'DefaultLeadStateIdMappingRuleForm' =>
                                               array('defaultStateId' => null)));
            $sanitizerUtilTypes        = LeadStateAttributeImportRules::getSanitizerUtilTypesInProcessingOrder();
            $sanitizedValue            = ImportSanitizerUtil::
                                         sanitizeValueBySanitizerTypes(
                                         $sanitizerUtilTypes, 'Contact', null, null,
                                         $columnMappingData, $importSanitizeResultsUtil);
            $this->assertNull($sanitizedValue);
            $this->assertFalse($importSanitizeResultsUtil->shouldSaveModel());
            $messages = $importSanitizeResultsUtil->getMessages();
            $this->assertEquals(1, count($messages));
            $compareMessage = 'Contact - The status is required.  Neither a value nor a default was specified.';
            $this->assertEquals($compareMessage, $messages[0]);

            //Test a required contact state with a valid value, and a default value. The valid value should come through.
            $importSanitizeResultsUtil = new ImportSanitizeResultsUtil();
            $columnMappingData         = array('type' => 'importColumn', 'mappingRulesData' => array(
                                               'DefaultLeadStateIdMappingRuleForm' =>
                                               array('defaultStateId' => $contactStates[0]->id)));
            $sanitizerUtilTypes        = LeadStateAttributeImportRules::getSanitizerUtilTypesInProcessingOrder();
            $sanitizedValue            = ImportSanitizerUtil::
                                         sanitizeValueBySanitizerTypes(
                                         $sanitizerUtilTypes, 'Contact', null, $contactStates[1]->id,
                                         $columnMappingData, $importSanitizeResultsUtil);
            $this->assertEquals($contactStates[1], $sanitizedValue);
            $this->assertTrue($importSanitizeResultsUtil->shouldSaveModel());
            $messages = $importSanitizeResultsUtil->getMessages();
            $this->assertEquals(0, count($messages));

            //Test a required contact state with no value, and a default value.
            $importSanitizeResultsUtil = new ImportSanitizeResultsUtil();
            $columnMappingData         = array('type' => 'importColumn', 'mappingRulesData' => array(
                                               'DefaultLeadStateIdMappingRuleForm' =>
                                               array('defaultStateId' => $contactStates[0]->id)));
            $sanitizerUtilTypes        = LeadStateAttributeImportRules::getSanitizerUtilTypesInProcessingOrder();
            $sanitizedValue            = ImportSanitizerUtil::
                                         sanitizeValueBySanitizerTypes(
                                         $sanitizerUtilTypes, 'Contact', null, null,
                                         $columnMappingData, $importSanitizeResultsUtil);
            $this->assertEquals($contactStates[0], $sanitizedValue);
            $this->assertTrue($importSanitizeResultsUtil->shouldSaveModel());
            $messages = $importSanitizeResultsUtil->getMessages();
            $this->assertEquals(0, count($messages));

            //Test a required contact state with a value that is invalid
            $importSanitizeResultsUtil = new ImportSanitizeResultsUtil();
            $columnMappingData         = array('type' => 'importColumn', 'mappingRulesData' => array(
                                               'DefaultLeadStateIdMappingRuleForm' =>
                                               array('defaultValue' => null)));
            $sanitizerUtilTypes        = LeadStateAttributeImportRules::getSanitizerUtilTypesInProcessingOrder();
            $sanitizedValue            = ImportSanitizerUtil::
                                         sanitizeValueBySanitizerTypes(
                                         $sanitizerUtilTypes, 'Contact', null, 'somethingnotright',
                                         $columnMappingData, $importSanitizeResultsUtil);
            $this->assertFalse($importSanitizeResultsUtil->shouldSaveModel());
            $messages = $importSanitizeResultsUtil->getMessages();
            $this->assertEquals(1, count($messages));
            $compareMessage = 'Contact - The status specified does not exist.';
            $this->assertEquals($compareMessage, $messages[0]);

            //Test a required contact state with a state that is for leads, not contacts.
            $importSanitizeResultsUtil = new ImportSanitizeResultsUtil();
            $columnMappingData         = array('type' => 'importColumn', 'mappingRulesData' => array(
                                               'DefaultLeadStateIdMappingRuleForm' =>
                                               array('defaultValue' => null)));
            $sanitizerUtilTypes        = LeadStateAttributeImportRules::getSanitizerUtilTypesInProcessingOrder();
            $sanitizedValue            = ImportSanitizerUtil::
                                         sanitizeValueBySanitizerTypes(
                                         $sanitizerUtilTypes, 'Contact', null, $contactStates[5]->id,
                                         $columnMappingData, $importSanitizeResultsUtil);
            $this->assertFalse($importSanitizeResultsUtil->shouldSaveModel());
            $messages = $importSanitizeResultsUtil->getMessages();
            $this->assertEquals(1, count($messages));
            $compareMessage = 'Contact - The status specified is invalid.';
            $this->assertEquals($compareMessage, $messages[0]);
        }
    }
?>