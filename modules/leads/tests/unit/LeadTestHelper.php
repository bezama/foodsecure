<?php


    class LeadTestHelper
    {
        public static function createLeadbyNameForOwner($firstName, $owner)
        {
            ContactsModule::loadStartingData();
            $contact = new Contact();
            $contact->firstName  = $firstName;
            $contact->lastName   = $firstName.'son';
            $contact->owner      = $owner;
            $contact->state      = LeadsUtil::getStartingState();
            $saved               = $contact->save();
            assert('$saved');
            return $contact;
        }

        public static function createLeadWithAccountByNameForOwner($firstName, $owner, $account)
        {
            ContactsModule::loadStartingData();
            $contact = new Contact();
            $contact->firstName  = $firstName;
            $contact->lastName   = $firstName.'son';
            $contact->owner      = $owner;
            $contact->account    = $account;
            $contact->state      = LeadsUtil::getStartingState();
            $saved               = $contact->save();
            assert('$saved');
            return $contact;
        }
    }
?>