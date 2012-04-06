<?php


    class AccountEditAndDetailsViewTest extends BaseTest
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

            $account = new Account();
            $view = new AccountEditAndDetailsView('Details', 'whatever', 'whatever', $account);
            $originalMetadata = AccountEditAndDetailsView::getMetadata($user);
            $metadataIn = $originalMetadata;
            $metadataIn['perUser']['junk1'] = 'stuff1';
            $metadataIn['global'] ['junk2'] = 'stuff2';
            $view->setMetadata($metadataIn, $user);
            $metadataOut = AccountEditAndDetailsView::getMetadata($user);
            $this->assertNotEquals($originalMetadata, $metadataOut);
            $this->assertEquals   ($metadataIn,       $metadataOut);
        }
    }
?>
