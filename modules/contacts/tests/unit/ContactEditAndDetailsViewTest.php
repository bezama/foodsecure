<?php


    class ContactEditAndDetailsViewTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function testSetAndGetMetadata()
        {
            Yii::app()->user->userModel = User::getByUsername('super');

            $user = UserTestHelper::createBasicUser('Steven');

            $contact = new Contact();
            $view = new ContactEditAndDetailsView('Details', 'whatever', 'whatever', $contact);
            $originalMetadata = ContactEditAndDetailsView::getMetadata($user);
            $metadataIn = $originalMetadata;
            $metadataIn['perUser']['junk1'] = 'stuff1';
            $metadataIn['global'] ['junk2'] = 'stuff2';
            $view->setMetadata($metadataIn, $user);
            $metadataOut = ContactEditAndDetailsView::getMetadata($user);
            $this->assertNotEquals($originalMetadata, $metadataOut);
            $this->assertEquals   ($metadataIn,       $metadataOut);
        }
    }
?>
