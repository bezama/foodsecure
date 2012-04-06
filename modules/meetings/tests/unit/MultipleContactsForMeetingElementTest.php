<?php


    class MultipleContactsForMeetingElementTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            $loaded = ContactsModule::loadStartingData();
        }

        public function testRenderHtmlContentLabelFromContactAndKeyword()
        {
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            $contact  = new Contact();
            $contact->firstName = 'johnny';
            $contact->lastName  = 'five';
            $contact->owner     = $super;
            $contact->state        = ContactState::getById(5);
            $contact->primaryEmail = new Email();
            $contact->primaryEmail->emailAddress = 'a@a.com';
            $this->assertTrue($contact->save());

            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            $contact2  = new Contact();
            $contact2->firstName = 'johnny';
            $contact2->lastName  = 'six';
            $contact2->owner     = $super;
            $contact2->state        = ContactState::getById(5);
            $contact2->primaryEmail = new Email();
            $contact2->primaryEmail->emailAddress = 'a@a.com';
            $contact2->secondaryEmail = new Email();
            $contact2->secondaryEmail->emailAddress = 'b@b.com';
            $this->assertTrue($contact2->save());

            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            $contact3  = new Contact();
            $contact3->firstName = 'johnny';
            $contact3->lastName  = 'seven';
            $contact3->owner     = $super;
            $contact3->state        = ContactState::getById(5);
            $this->assertTrue($contact3->save());

            $content = MultipleContactsForMeetingElement::renderHtmlContentLabelFromContactAndKeyword($contact, 'asdad');
            $this->assertEquals('johnny five&#160&#160<b>a@a.com</b>', $content);

            $content = MultipleContactsForMeetingElement::renderHtmlContentLabelFromContactAndKeyword($contact2, 'b@b');
            $this->assertEquals('johnny six&#160&#160<b>b@b.com</b>', $content);

            $content = MultipleContactsForMeetingElement::renderHtmlContentLabelFromContactAndKeyword($contact2, 'cc');
            $this->assertEquals('johnny six&#160&#160<b>a@a.com</b>', $content);

            $content = MultipleContactsForMeetingElement::renderHtmlContentLabelFromContactAndKeyword($contact3, 'cx');
            $this->assertEquals('johnny seven', $content);
        }
    }
?>
