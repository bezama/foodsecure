<?php


    class LeadImportTest extends ImportBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            Yii::import('application.extensions.fosainc.framework.data.*');
            Yii::import('application.modules.accounts.data.*');
            $defaultDataMaker = new AccountsDefaultDataMaker();
            $defaultDataMaker->make();
            Yii::import('application.modules.contacts.data.*');
            $defaultDataMaker = new ContactsDefaultDataMaker();
            $defaultDataMaker->make();
            Currency::getAll(); //forces base currency to be created.
            ContactsModule::loadStartingData();
        }

        public function testSimpleUserImportWhereAllRowsSucceed()
        {
            Yii::app()->user->userModel            = User::getByUsername('super');
            $contacts                              = Contact::getAll();
            $this->assertEquals(0, count($contacts));
            $import                                = new Import();
            $serializedData['importRulesType']     = 'Leads';
            $serializedData['firstRowIsHeaderRow'] = true;
            $import->serializedData                = serialize($serializedData);
            $this->assertTrue($import->save());

            ImportTestHelper::
            createTempTableByFileNameAndTableName('importTest.csv', $import->getTempTableName(),
                                                  Yii::getPathOfAlias('application.modules.leads.tests.unit.files'));

            $this->assertEquals(4, ImportDatabaseUtil::getCount($import->getTempTableName())); // includes header rows.

            $currency = Currency::getByCode(Yii::app()->currencyHelper->getBaseCode());

            $mappingData = array(
                'column_0'  => ImportMappingUtil::makeStringColumnMappingData       ('firstName'),
                'column_1'  => ImportMappingUtil::makeStringColumnMappingData       ('lastName'),
                'column_2'  => ImportMappingUtil::makeStringColumnMappingData       ('jobTitle'),
                'column_3'  => ImportMappingUtil::makeStringColumnMappingData       ('officePhone'),
                'column_4'  => ImportMappingUtil::makeStringColumnMappingData       ('officeFax'),
                'column_5'  => ImportMappingUtil::makeStringColumnMappingData       ('department'),
                'column_6'  => ImportMappingUtil::makeUrlColumnMappingData          ('website'),
                'column_7'  => ImportMappingUtil::makeTextAreaColumnMappingData     ('description'),
                'column_8'  => ImportMappingUtil::makeStringColumnMappingData       ('primaryAddress__city'),
                'column_9'  => ImportMappingUtil::makeStringColumnMappingData       ('primaryAddress__country'),
                'column_10' => ImportMappingUtil::makeStringColumnMappingData       ('primaryAddress__postalCode'),
                'column_11' => ImportMappingUtil::makeStringColumnMappingData       ('primaryAddress__state'),
                'column_12' => ImportMappingUtil::makeStringColumnMappingData       ('primaryAddress__street1'),
                'column_13' => ImportMappingUtil::makeStringColumnMappingData       ('primaryAddress__street2'),
                'column_14' => ImportMappingUtil::makeEmailColumnMappingData        ('primaryEmail__emailAddress'),
                'column_15' => ImportMappingUtil::makeBooleanColumnMappingData      ('primaryEmail__isInvalid'),
                'column_16' => ImportMappingUtil::makeBooleanColumnMappingData      ('primaryEmail__optOut'),
                'column_17' => ImportMappingUtil::makeDropDownColumnMappingData     ('source'),
                'column_18' => LeadImportTestHelper::makeStateColumnMappingData        (),
                'column_19' => ImportMappingUtil::makeDropDownColumnMappingData     ('industry'),
                'column_20' => ImportMappingUtil::makeStringColumnMappingData       ('companyName'),
            );

            $importRules  = ImportRulesUtil::makeImportRulesByType('Leads');
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
            $contacts = Contact::getAll();
            $this->assertEquals(3, count($contacts));

            $contacts = Contact::getByName('contact1 contact1son');
            $this->assertEquals(1,                         count($contacts[0]));
            $this->assertEquals('contact1',                $contacts[0]->firstName);
            $this->assertEquals('contact1son',             $contacts[0]->lastName);
            $this->assertEquals('president',               $contacts[0]->jobTitle);
            $this->assertEquals(123456,                    $contacts[0]->officePhone);
            $this->assertEquals(555,                       $contacts[0]->officeFax);
            $this->assertEquals('executive',               $contacts[0]->department);
            $this->assertEquals('http://www.contact1.com', $contacts[0]->website);
            $this->assertEquals('desc1',                   $contacts[0]->description);
            $this->assertEquals('city1',                   $contacts[0]->primaryAddress->city);
            $this->assertEquals('country1',                $contacts[0]->primaryAddress->country);
            $this->assertEquals('postal1',                 $contacts[0]->primaryAddress->postalCode);
            $this->assertEquals('state1',                  $contacts[0]->primaryAddress->state);
            $this->assertEquals('street11',                $contacts[0]->primaryAddress->street1);
            $this->assertEquals('street21',                $contacts[0]->primaryAddress->street2);
            $this->assertEquals('a@a.com',                 $contacts[0]->primaryEmail->emailAddress);
            $this->assertEquals(null,                      $contacts[0]->primaryEmail->isInvalid);
            $this->assertEquals(null,                      $contacts[0]->primaryEmail->optOut);
            $this->assertEquals('Self-Generated',          $contacts[0]->source->value);
            $this->assertEquals('New',                     $contacts[0]->state->name);
            $this->assertEquals('Automotive',              $contacts[0]->industry->value);
            $this->assertEquals('company1',                $contacts[0]->companyName);

            $contacts = Contact::getByName('contact2 contact2son');
            $this->assertEquals(1,                         count($contacts[0]));
            $this->assertEquals('contact2',                $contacts[0]->firstName);
            $this->assertEquals('contact2son',             $contacts[0]->lastName);
            $this->assertEquals('president2',              $contacts[0]->jobTitle);
            $this->assertEquals(223456,                    $contacts[0]->officePhone);
            $this->assertEquals(655,                       $contacts[0]->officeFax);
            $this->assertEquals('executive2',              $contacts[0]->department);
            $this->assertEquals('http://www.contact2.com', $contacts[0]->website);
            $this->assertEquals('desc2',                   $contacts[0]->description);
            $this->assertEquals('city2',                   $contacts[0]->primaryAddress->city);
            $this->assertEquals('country2',                $contacts[0]->primaryAddress->country);
            $this->assertEquals('postal2',                 $contacts[0]->primaryAddress->postalCode);
            $this->assertEquals('state2',                  $contacts[0]->primaryAddress->state);
            $this->assertEquals('street12',                $contacts[0]->primaryAddress->street1);
            $this->assertEquals('street22',                $contacts[0]->primaryAddress->street2);
            $this->assertEquals('b@b.com',                 $contacts[0]->primaryEmail->emailAddress);
            $this->assertEquals(null,                      $contacts[0]->primaryEmail->isInvalid);
            $this->assertEquals(null,                      $contacts[0]->primaryEmail->optOut);
            $this->assertEquals('Tradeshow',               $contacts[0]->source->value);
            $this->assertEquals('Recycled',                $contacts[0]->state->name);
            $this->assertEquals('Banking',                 $contacts[0]->industry->value);
            $this->assertEquals('company2',                $contacts[0]->companyName);

            $contacts = Contact::getByName('contact3 contact3son');
            $this->assertEquals(1,                         count($contacts[0]));
            $this->assertEquals('contact3',                $contacts[0]->firstName);
            $this->assertEquals('contact3son',             $contacts[0]->lastName);
            $this->assertEquals('president3',              $contacts[0]->jobTitle);
            $this->assertEquals(323456,                    $contacts[0]->officePhone);
            $this->assertEquals(755,                       $contacts[0]->officeFax);
            $this->assertEquals('executive3',              $contacts[0]->department);
            $this->assertEquals('http://www.contact3.com', $contacts[0]->website);
            $this->assertEquals('desc3',                   $contacts[0]->description);
            $this->assertEquals('city3',                   $contacts[0]->primaryAddress->city);
            $this->assertEquals('country3',                $contacts[0]->primaryAddress->country);
            $this->assertEquals('postal3',                 $contacts[0]->primaryAddress->postalCode);
            $this->assertEquals('state3',                  $contacts[0]->primaryAddress->state);
            $this->assertEquals('street13',                $contacts[0]->primaryAddress->street1);
            $this->assertEquals('street23',                $contacts[0]->primaryAddress->street2);
            $this->assertEquals('c@c.com',                 $contacts[0]->primaryEmail->emailAddress);
            $this->assertEquals('1',                       $contacts[0]->primaryEmail->isInvalid);
            $this->assertEquals('1',                       $contacts[0]->primaryEmail->optOut);
            $this->assertEquals('Inbound Call',            $contacts[0]->source->value);
            $this->assertEquals('New',                     $contacts[0]->state->name);
            $this->assertEquals('Energy',                  $contacts[0]->industry->value);
            $this->assertEquals('company3',                $contacts[0]->companyName);

            //Confirm 3 rows were processed as 'created'.
            $this->assertEquals(3, ImportDatabaseUtil::getCount($import->getTempTableName(), "status = "
                                                                 . ImportRowDataResultsUtil::CREATED));

            //Confirm that 0 rows were processed as 'updated'.
            $this->assertEquals(0, ImportDatabaseUtil::getCount($import->getTempTableName(),  "status = "
                                                                 . ImportRowDataResultsUtil::UPDATED));

            //Confirm 0 rows were processed as 'errors'.
            $this->assertEquals(0, ImportDatabaseUtil::getCount($import->getTempTableName(),  "status = "
                                                                 . ImportRowDataResultsUtil::ERROR));

            $beansWithErrors = ImportDatabaseUtil::getSubset($import->getTempTableName(),     "status = "
                                                                 . ImportRowDataResultsUtil::ERROR);
            $this->assertEquals(0, count($beansWithErrors));
        }
    }
?>