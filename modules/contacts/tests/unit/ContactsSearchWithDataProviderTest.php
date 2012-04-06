<?php


    /**
     * Tests on contact pecific search permutations.
     */
    class ContactsSearchWithDataProviderTest extends fosaDataProviderBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            ContactsModule::loadStartingData();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
        }

        public function testSearchOnContactState()
        {
            //Test member of search.
            $_FAKEPOST['Contact'] = array();
            $_FAKEPOST['Contact']['state']['id'] = '4';
            $metadataAdapter = new SearchDataProviderMetadataAdapter(
                new Contact(false),
                1,
                $_FAKEPOST['Contact']
            );
            $searchAttributeData = $metadataAdapter->getAdaptedMetadata();
            $joinTablesAdapter   = new RedBeanModelJoinTablesQueryAdapter('Contact');
            $quote        = DatabaseCompatibilityUtil::getQuote();
            $where        = RedBeanModelDataProvider::makeWhere('Contact', $searchAttributeData, $joinTablesAdapter);
            $compareWhere = "({$quote}contact{$quote}.{$quote}state_contactstate_id{$quote} = 4)";
            $this->assertEquals($compareWhere, $where);

            //Now test that the joinTablesAdapter has correct information.
            $this->assertEquals(0, $joinTablesAdapter->getFromTableJoinCount());
            $this->assertEquals(0, $joinTablesAdapter->getLeftTableJoinCount());

            //Make sure the sql runs properly.
            $dataProvider = new RedBeanModelDataProvider('Contact', null, false, $searchAttributeData);
            $data = $dataProvider->getData();
        }

        public function testFullNameOnContactsSearchFormSearch()
        {
            $super                      = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            $_FAKEPOST['Contact'] = array();
            $_FAKEPOST['Contact']['fullName'] = 'Jackie Tyler';
            $metadataAdapter = new SearchDataProviderMetadataAdapter(
                new ContactsSearchForm(new Contact(false)),
                1,
                $_FAKEPOST['Contact']
            );
            $searchAttributeData = $metadataAdapter->getAdaptedMetadata();
            $compareData = array('clauses' => array(1 => array('attributeName' => 'firstName',
                                                          'operatorType'  => 'startsWith',
                                                          'value'         => 'Jackie Tyler'),
                                                    2 => array('attributeName' => 'lastName',
                                                          'operatorType'  => 'startsWith',
                                                          'value'         => 'Jackie Tyler'),
                                                    3 => array('concatedAttributeNames' => array('firstName', 'lastName'),
                                                          'operatorType'  => 'startsWith',
                                                          'value'         => 'Jackie Tyler')),
                                 'structure' => '(1 or 2 or 3)');
            $this->assertEquals($compareData, $searchAttributeData);

            $joinTablesAdapter   = new RedBeanModelJoinTablesQueryAdapter('Contact');
            $quote        = DatabaseCompatibilityUtil::getQuote();
            $where        = RedBeanModelDataProvider::makeWhere('Contact', $searchAttributeData, $joinTablesAdapter);
            $compareWhere  = "(({$quote}person{$quote}.{$quote}firstname{$quote} like 'Jackie Tyler%') or ";
            $compareWhere .= "({$quote}person{$quote}.{$quote}lastname{$quote} like 'Jackie Tyler%') or ";
            $compareWhere .= "(concat({$quote}person{$quote}.{$quote}firstname{$quote}, ' ', ";
            $compareWhere .= "{$quote}person{$quote}.{$quote}lastname{$quote}) like 'Jackie Tyler%'))";
            $this->assertEquals($compareWhere, $where);

            //Now test that the joinTablesAdapter has correct information.
            $this->assertEquals(1, $joinTablesAdapter->getFromTableJoinCount());
            $this->assertEquals(0, $joinTablesAdapter->getLeftTableJoinCount());
            $fromTables = $joinTablesAdapter->getFromTablesAndAliases();
            $this->assertEquals('person', $fromTables[0]['tableName']);

            //Make sure the sql runs properly.
            $dataProvider = new RedBeanModelDataProvider('Contact', null, false, $searchAttributeData);
            $data = $dataProvider->getData();

            $this->assertEquals(0, count($data));

            ContactTestHelper::createContactByNameForOwner('Dino', $super);

            $dataProvider->getTotalItemCount(true); //refreshes the total item count
            $data = $dataProvider->getData();
            $this->assertEquals(0, count($data));

            ContactTestHelper::createContactByNameForOwner('Jackie', $super);

            $dataProvider->getTotalItemCount(true); //refreshes the total item count
            $data = $dataProvider->getData();
            $this->assertEquals(0, count($data));

            ContactsModule::loadStartingData();
            $contact = new Contact();
            $contact->firstName  = 'Jackie';
            $contact->lastName   = 'Tyler';
            $contact->owner      = $super;
            $contact->state      = ContactsUtil::getStartingState();
            $this->assertTrue($contact->save());

            $dataProvider->getTotalItemCount(true); //refreshes the total item count
            $data = $dataProvider->getData(true);

            $this->assertEquals(1, count($data));
            $this->assertEquals($contact->id, $data[0]->id);
        }
    }
?>