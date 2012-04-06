<?php


    class ContactStateTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function testCreateAndGetContactState()
        {
            $state = new ContactState();
            $state->name = 'First State';
            $state->order = 0;
            $this->assertTrue($state->save());
            $id = $state->id;
            unset($state);
            $state = ContactState::getById($id);
            $this->assertEquals('First State', $state->name);
            $this->assertEquals(0, $state->order);
            $state->delete();
        }

        public function testContactStateModelAttributesAdapter()
        {
            Yii::app()->user->userModel = User::getByUsername('super');
            $this->assertTrue(ContactsModule::loadStartingData());
            $this->assertEquals(6, count(ContactState::GetAll()));

            $attributeForm = AttributesFormFactory::createAttributeFormByAttributeName(new Contact(), 'state');
            $compareData = array(
                0 => 'New',
                1 => 'In Progress',
                2 => 'Recycled',
                3 => 'Dead',
                4 => 'Qualified',
                5 => 'Customer',
            );
            $this->assertEquals($compareData, $attributeForm->contactStatesData);
            $this->assertEquals(null, $attributeForm->contactStatesLabels);
            $this->assertEquals(4, $attributeForm->startingStateOrder);

            //Now add new values.
            $attributeForm->contactStatesData = array(
                0 => 'New',
                1 => 'In Progress',
                2 => 'Recycled',
                3 => 'Dead',
                4 => 'Qualified',
                5 => 'Customer',
                6 => 'AAA',
                7 => 'BBB',
            );
            $contactStatesLabels = array(
                'fr' => array('New', 'In ProgressFr', 'RecycledFr', 'DeadFr', 'QualifiedFr', 'CustomerFr', 'AAAFr', 'BBBFr')
            );
            $attributeForm->contactStatesLabels = $contactStatesLabels;
            $attributeForm->startingStateOrder  = 5;
            $adapter = new ContactStateModelAttributesAdapter(new Contact());
            $adapter->setAttributeMetadataFromForm($attributeForm);
            $attributeForm = AttributesFormFactory::createAttributeFormByAttributeName(new Contact(), 'state');
            $compareData = array(
                0 => 'New',
                1 => 'In Progress',
                2 => 'Recycled',
                3 => 'Dead',
                4 => 'Qualified',
                5 => 'Customer',
                6 => 'AAA',
                7 => 'BBB',
            );
            $this->assertEquals($compareData, $attributeForm->contactStatesData);
            $this->assertEquals($contactStatesLabels, $attributeForm->contactStatesLabels);
            $contactState = ContactState::getByName('Customer');
            $this->assertEquals(5, $contactState[0]->order);
            $this->assertEquals(5, $attributeForm->startingStateOrder);

            //Test removing existing values.
            $attributeForm->contactStatesData = array(
                0 => 'New',
                1 => 'In Progress',
                2 => 'Recycled',
                3 => 'Customer',
                4 => 'AAA',
                5 => 'BBB',
            );
            $attributeForm->startingStateOrder = 5;
            $adapter = new ContactStateModelAttributesAdapter(new Contact());
            $adapter->setAttributeMetadataFromForm($attributeForm);
            $attributeForm = AttributesFormFactory::createAttributeFormByAttributeName(new Contact(), 'state');
            $compareData = array(
                0 => 'New',
                1 => 'In Progress',
                2 => 'Recycled',
                3 => 'Customer',
                4 => 'AAA',
                5 => 'BBB',
            );
            $this->assertEquals($compareData, $attributeForm->contactStatesData);
            $this->assertEquals(5, $attributeForm->startingStateOrder);

            //Test switching order of existing values.
            $attributeForm->contactStatesData = array(
                0 => 'New',
                3 => 'In Progress',
                5 => 'Recycled',
                1 => 'Customer',
                4 => 'AAA',
                2 => 'BBB',
            );
            $attributeForm->startingStateOrder = 2;
            $adapter = new ContactStateModelAttributesAdapter(new Contact());
            $adapter->setAttributeMetadataFromForm($attributeForm);
            $attributeForm = AttributesFormFactory::createAttributeFormByAttributeName(new Contact(), 'state');
            $compareData = array(
                0 => 'New',
                3 => 'In Progress',
                5 => 'Recycled',
                1 => 'Customer',
                4 => 'AAA',
                2 => 'BBB',
            );
            $this->assertEquals($compareData, $attributeForm->contactStatesData);
            $this->assertEquals(2, $attributeForm->startingStateOrder);

            //Test switching order of existing values and adding new values mixed in.
            $attributeForm->contactStatesData = array(
                3 => 'New',
                6 => 'In Progress',
                5 => 'Recycled',
                1 => 'Customer',
                4 => 'AAA',
                2 => 'BBB',
                0 => 'CCC',
            );
            $attributeForm->startingStateOrder = 2;
            $adapter = new ContactStateModelAttributesAdapter(new Contact());
            $adapter->setAttributeMetadataFromForm($attributeForm);
            $attributeForm = AttributesFormFactory::createAttributeFormByAttributeName(new Contact(), 'state');
            $compareData = array(
                3 => 'New',
                6 => 'In Progress',
                5 => 'Recycled',
                1 => 'Customer',
                4 => 'AAA',
                2 => 'BBB',
                0 => 'CCC',
            );
            $this->assertEquals($compareData, $attributeForm->contactStatesData);
            $this->assertEquals(2, $attributeForm->startingStateOrder);
        }
    }
?>