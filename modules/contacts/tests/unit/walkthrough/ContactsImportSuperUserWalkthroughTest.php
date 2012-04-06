<?php


    /**
     * Contacts Module Import Super User Walkthrough.
     * Walkthrough for the super user of various import specific actions.
     */
    class ContactsImportSuperUserWalkthroughTest extends ImportWalkthroughBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            ContactsModule::loadStartingData();
        }

        public function testSuperUserMappingRulesEditActionAllAttributeIndexAndDerivedTypes()
        {
            $super = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');

            $import = new Import();
            $import->serializedData = serialize(array('importRulesType' => 'Contacts'));
            $this->assertTrue($import->save());

            //Test contact specific attribute indexes and derived attribute types
            $this->runMappingRulesEditAction($import->id, 'FullName');
            $this->runMappingRulesEditAction($import->id, 'ContactState');
        }
    }
?>