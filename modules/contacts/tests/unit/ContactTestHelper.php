<?php


    class ContactTestHelper
    {
        public static function createContactByNameForOwner($firstName, $owner)
        {
            ContactsModule::loadStartingData();
            $contact = new Contact();
            $contact->firstName  = $firstName;
            $contact->lastName   = $firstName.'son';
            $contact->owner      = $owner;
            $contact->state      = ContactsUtil::getStartingState();
            $saved               = $contact->save();
            assert('$saved');
            return $contact;
        }

        public static function createContactWithAccountByNameForOwner($firstName, $owner, $account)
        {
            ContactsModule::loadStartingData();
            $contact = new Contact();
            $contact->firstName  = $firstName;
            $contact->lastName   = $firstName.'son';
            $contact->owner      = $owner;
            $contact->account    = $account;
            $contact->state      = ContactsUtil::getStartingState();
            $saved               = $contact->save();
            assert('$saved');
            return $contact;
        }
    }
?>