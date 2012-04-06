<?php


    class ContactsUtilTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            SecurityTestHelper::createUsers();
        }

        public function testResolveContactStateAdapterByModulesUserHasAccessTo()
        {
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            $bobby   = User::getByUsername('bobby');
            $this->assertEquals(Right::DENY, $bobby->getEffectiveRight('ContactsModule', ContactsModule::RIGHT_ACCESS_CONTACTS));
            $this->assertEquals(Right::DENY, $bobby->getEffectiveRight('LeadsModule', LeadsModule::RIGHT_ACCESS_LEADS));

            //test Contact model where has no access to either the leads or contacts module.
            $adapterName = ContactsUtil::resolveContactStateAdapterByModulesUserHasAccessTo('ContactsModule', 'LeadsModule', $bobby);
            $this->assertFalse($adapterName);

            //test Contact model where user has access to only the leads module
            $bobby->setRight('LeadsModule', LeadsModule::RIGHT_ACCESS_LEADS);
            $this->assertTrue($bobby->save());
            $adapterName = ContactsUtil::resolveContactStateAdapterByModulesUserHasAccessTo('ContactsModule', 'LeadsModule', $bobby);
            $this->assertEquals('LeadsStateMetadataAdapter', $adapterName);

            //test Contact model where user has access to only the contacts module
            $bobby->removeRight('LeadsModule', LeadsModule::RIGHT_ACCESS_LEADS);
            $bobby->setRight('ContactsModule', ContactsModule::RIGHT_ACCESS_CONTACTS);
            $this->assertTrue($bobby->save());
            $adapterName = ContactsUtil::resolveContactStateAdapterByModulesUserHasAccessTo('ContactsModule', 'LeadsModule', $bobby);
            $this->assertEquals('ContactsStateMetadataAdapter', $adapterName);

            //test Contact model where user has access to both the contacts and leads module.
            $bobby->setRight('LeadsModule', LeadsModule::RIGHT_ACCESS_LEADS);
            $this->assertTrue($bobby->save());
            $adapterName = ContactsUtil::resolveContactStateAdapterByModulesUserHasAccessTo('ContactsModule', 'LeadsModule', $bobby);
            $this->assertNull($adapterName);
        }

        /**
         * @depends testResolveContactStateAdapterByModulesUserHasAccessTo
         */
        public function testGetContactStateDataFromStartingStateOnAndKeyedById()
        {
            $this->assertTrue(ContactsModule::loadStartingData());
            $this->assertEquals(6, count(ContactState::GetAll()));
            $contactStates = ContactsUtil::GetContactStateDataFromStartingStateOnAndKeyedById();
            $this->assertEquals(2, count($contactStates));
        }

        /**
         * @depends testGetContactStateDataFromStartingStateOnAndKeyedById
         */
        public function testGetContactStateLabelsKeyedByLanguageAndOrder()
        {
            $data                        = ContactsUtil::getContactStateLabelsKeyedByLanguageAndOrder();
            $compareData                 = null;
            $this->assertEquals($compareData, $data);
            $states                      = ContactState::getByName('Qualified');
            $states[0]->serializedLabels = serialize(array('fr' => 'QualifiedFr', 'de' => 'QualifiedDe'));
            $this->assertTrue($states[0]->save());
            $data                        = ContactsUtil::getContactStateLabelsKeyedByLanguageAndOrder();
            $compareData                 = array('fr' => array($states[0]->order => 'QualifiedFr'),
                                                 'de' => array($states[0]->order => 'QualifiedDe'));
            $this->assertEquals($compareData, $data);
        }

        /**
         * @depends testGetContactStateLabelsKeyedByLanguageAndOrder
         */
        public function testResolveStateLabelByLanguage()
        {
            $states = ContactState::getByName('Qualified');
            $this->assertEquals('Qualified',   ContactsUtil::resolveStateLabelByLanguage($states[0], 'en'));
            $this->assertEquals('QualifiedFr', ContactsUtil::resolveStateLabelByLanguage($states[0], 'fr'));
            $this->assertEquals('QualifiedDe', ContactsUtil::resolveStateLabelByLanguage($states[0], 'de'));
        }

        /**
         * @depends testResolveStateLabelByLanguage
         */
        public function testGetContactStateDataFromStartingStateKeyedByIdAndLabelByLanguage()
        {
            $qualifiedStates = ContactState::getByName('Qualified');
            $customerStates = ContactState::getByName('Customer');
            $data = ContactsUtil::getContactStateDataFromStartingStateKeyedByIdAndLabelByLanguage('en');
            $compareData = array($qualifiedStates[0]->id => 'Qualified',
                                $customerStates[0]->id  => 'Customer');
            $this->assertEquals($compareData, $data);
            $data = ContactsUtil::getContactStateDataFromStartingStateKeyedByIdAndLabelByLanguage('fr');
            $compareData = array($qualifiedStates[0]->id => 'QualifiedFr',
                                $customerStates[0]->id  => 'Client');
            $this->assertEquals($compareData, $data);
        }
    }
?>