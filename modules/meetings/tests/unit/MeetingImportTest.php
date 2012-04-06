<?php


    class MeetingImportTest extends ActivityImportBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            $super                      = SecurityTestHelper::createSuperAdmin();
            Yii::app()->user->userModel = $super;
            Yii::app()->timeZoneHelper->load();
            MeetingTestHelper::createCategories();
        }

        public function testSimpleUserImportWhereAllRowsSucceed()
        {
            Yii::app()->user->userModel            = User::getByUsername('super');

            $meetings                              = Meeting::getAll();
            $this->assertEquals(0, count($meetings));
            $import                                = new Import();
            $serializedData['importRulesType']     = 'Meetings';
            $serializedData['firstRowIsHeaderRow'] = true;
            $import->serializedData                = serialize($serializedData);
            $this->assertTrue($import->save());

            ImportTestHelper::
            createTempTableByFileNameAndTableName('importAnalyzerTest.csv', $import->getTempTableName(),
                                                  Yii::getPathOfAlias('application.modules.meetings.tests.unit.files'));

            $this->assertEquals(4, ImportDatabaseUtil::getCount($import->getTempTableName())); // includes header rows.

            $mappingData = array(
                'column_0' => ImportMappingUtil::makeStringColumnMappingData       ('name'),
                'column_1' => ImportMappingUtil::makeStringColumnMappingData       ('location'),
                'column_2' => ImportMappingUtil::makeDateTimeColumnMappingData     ('startDateTime'),
                'column_3' => ImportMappingUtil::makeDateTimeColumnMappingData     ('endDateTime'),
                'column_4' => ImportMappingUtil::makeDropDownColumnMappingData     ('category'),
                'column_5' => ImportMappingUtil::makeModelDerivedColumnMappingData ('AccountDerived'),
                'column_6' => ImportMappingUtil::makeModelDerivedColumnMappingData ('ContactDerived'),
                'column_7' => ImportMappingUtil::makeModelDerivedColumnMappingData ('OpportunityDerived'),
                'column_8' => ImportMappingUtil::makeTextAreaColumnMappingData     ('description'),
            );

            $importRules  = ImportRulesUtil::makeImportRulesByType('Meetings');
            $page         = 0;
            $config       = array('pagination' => array('pageSize' => 50)); //This way all rows are processed.
            $dataProvider = new ImportDataProvider($import->getTempTableName(), true, $config);
            $dataProvider->getPagination()->setCurrentPage($page);
            $importResultsUtil = new ImportResultsUtil($import);
            $messageLogger     = new ImportMessageLogger();
            ImportUtil::importByDataProvider($dataProvider,
                                             $importRules,
                                             $mappingData,
                                             $importResultsUtil,
                                             new ExplicitReadWriteModelPermissions(),
                                             $messageLogger);
            $importResultsUtil->processStatusAndMessagesForEachRow();

            //Confirm that 3 models where created.
            $meetings = Meeting::getAll();
            $this->assertEquals(3, count($meetings));

            $meetings = Meeting::getByName('meeting1');
            $this->assertEquals(1,                  count($meetings[0]));
            $this->assertEquals(1,                  count($meetings[0]->activityItems));
            $this->assertEquals('testAccount',      $meetings[0]->activityItems[0]->name);
            $this->assertEquals('Account',          get_class($meetings[0]->activityItems[0]));
            $this->assertEquals('2011-12-22 05:03', substr($meetings[0]->latestDateTime, 0, -3));

            $meetings = Meeting::getByName('meeting2');
            $this->assertEquals(1,                  count($meetings[0]));
            $this->assertEquals(1,                  count($meetings[0]->activityItems));
            $this->assertEquals('testContact',      $meetings[0]->activityItems[0]->firstName);
            $this->assertEquals('Contact',          get_class($meetings[0]->activityItems[0]));
            $this->assertEquals('2011-12-22 05:03', substr($meetings[0]->latestDateTime, 0, -3));

            $meetings = Meeting::getByName('meeting3');
            $this->assertEquals(1,                  count($meetings[0]));
            $this->assertEquals(1,                  count($meetings[0]->activityItems));
            $this->assertEquals('testOpportunity',  $meetings[0]->activityItems[0]->name);
            $this->assertEquals('Opportunity',      get_class($meetings[0]->activityItems[0]));
            $this->assertEquals('2011-12-22 06:03', substr($meetings[0]->latestDateTime, 0, -3));

            //Confirm 10 rows were processed as 'created'.
            $this->assertEquals(3, ImportDatabaseUtil::getCount($import->getTempTableName(), "status = "
                                                                 . ImportRowDataResultsUtil::CREATED));

            //Confirm that 0 rows were processed as 'updated'.
            $this->assertEquals(0, ImportDatabaseUtil::getCount($import->getTempTableName(),  "status = "
                                                                 . ImportRowDataResultsUtil::UPDATED));

            //Confirm 2 rows were processed as 'errors'.
            $this->assertEquals(0, ImportDatabaseUtil::getCount($import->getTempTableName(),  "status = "
                                                                 . ImportRowDataResultsUtil::ERROR));

            $beansWithErrors = ImportDatabaseUtil::getSubset($import->getTempTableName(),     "status = "
                                                                 . ImportRowDataResultsUtil::ERROR);
            $this->assertEquals(0, count($beansWithErrors));
        }
    }
?>