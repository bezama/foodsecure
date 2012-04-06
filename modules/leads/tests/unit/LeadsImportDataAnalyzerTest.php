<?php


    class LeadsImportDataAnalyzerTest extends ImportBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            $super = SecurityTestHelper::createSuperAdmin();
            Yii::app()->user->userModel = $super;
            ContactsModule::loadStartingData();
        }

        public function testImportDataAnalysisResults()
        {
            $super                             = User::getByUsername('super');
            Yii::app()->user->userModel        = $super;
            $import                            = new Import();
            $serializedData['importRulesType'] = 'Leads';
            $import->serializedData            = serialize($serializedData);
            $this->assertTrue($import->save());

            ImportTestHelper::
            createTempTableByFileNameAndTableName('importAnalyzerTest.csv', $import->getTempTableName(),
                                                  Yii::getPathOfAlias('application.modules.leads.tests.unit.files'));
            $mappingData = array(
                'column_0'  => array('attributeIndexOrDerivedType' => 'LeadState',
                                     'type' => 'importColumn',
                                      'mappingRulesData' => array(
                                          'DefaultLeadStateIdMappingRuleForm' =>
                                          array('defaultStateId' => null))),
            );
            $serializedData                = unserialize($import->serializedData);
            $serializedData['mappingData'] = $mappingData;
            $import->serializedData        = serialize($serializedData);
            $this->assertTrue($import->save());

            $importRules  = ImportRulesUtil::makeImportRulesByType('Leads');
            $config       = array('pagination' => array('pageSize' => 2));
            //This test csv has a header row.
            $dataProvider = new ImportDataProvider($import->getTempTableName(), true, $config);

            //Run data analyzer
            $importDataAnalyzer = new ImportDataAnalyzer($importRules, $dataProvider);
            foreach ($mappingData as $columnName => $columnMappingData)
            {
                $importDataAnalyzer->analyzeByColumnNameAndColumnMappingData($columnName, $columnMappingData);
            }
            $messagesData = $importDataAnalyzer->getMessagesData();
            $compareData = array(
                'column_0' => array(
                    array('message' => '3 value(s) are not valid. Rows that have these values will be skipped upon import.',
                          'sanitizerUtilType' => 'LeadState', 'moreAvailable' => false),
                ),
            );
            $this->assertEquals($compareData, $messagesData);
            $importInstructionsData   = $importDataAnalyzer->getImportInstructionsData();
            $compareInstructionsData  = array();
            $this->assertEquals($compareInstructionsData, $importInstructionsData);
        }

            /**
         * @depends testImportDataAnalysisResults
         */
        public function testImportDataAnalysisUsingBatchAnalyzers()
        {
            Yii::app()->user->userModel        = User::getByUsername('super');

            $import                            = new Import();
            $serializedData['importRulesType'] = 'ImportModelTestItem';
            $import->serializedData            = serialize($serializedData);
            $this->assertTrue($import->save());
            ImportTestHelper::
            createTempTableByFileNameAndTableName('importAnalyzerTest.csv', $import->getTempTableName(),
                                                  Yii::getPathOfAlias('application.modules.leads.tests.unit.files'));

            $config       = array('pagination' => array('pageSize' => 2));
            $dataProvider = new ImportDataProvider($import->getTempTableName(), true, $config);

            //Test contact state sanitization by batch.
            $dataAnalyzer = new LeadStateBatchAttributeValueDataAnalyzer('Leads', null);
            $dataAnalyzer->runAndMakeMessages($dataProvider, 'column_0');
            $messages = $dataAnalyzer->getMessages();
            $this->assertEquals(1, count($messages));
            $compareMessage = '3 value(s) are not valid. Rows that have these values will be skipped upon import.';
            $this->assertEquals($compareMessage, $messages[0]);
        }
    }
?>
