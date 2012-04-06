<?php


    class ContactsRelatedListPortletTest extends BaseTest
    {
            public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            //Setup test data owned by the super user.
            $account = AccountTestHelper::createAccountByNameForOwner('superAccount', $super);
            AccountTestHelper::createAccountByNameForOwner           ('superAccount2', $super);
            ContactTestHelper::createContactWithAccountByNameForOwner('superContact', $super, $account);
        }

        public function testSaveAndRetrievePortlet()
        {
            $user = UserTestHelper::createBasicUser('Billy');
            $contacts = Contact::getByName('superContact superContactson');
            $portlet = new Portlet();
            $portlet->column    = 2;
            $portlet->position  = 5;
            $portlet->layoutId  = 'Test';
            $portlet->collapsed = true;
            $portlet->viewType  = 'ContactsForAccountRelatedList';
            $portlet->serializedViewData = serialize(array('title' => 'Testing Title'));
            $portlet->user      = $user;
            $this->assertTrue($portlet->save());
            $portlet = Portlet::getById($portlet->id);
            $params = array(
                'controllerId'         => 'test',
                'relationModuleId'     => 'test',
                'relationModel'        => $contacts[0],
                'redirectUrl'          => 'someRedirect',
            );
            $portlet->params = $params;
            $unserializedViewData = unserialize($portlet->serializedViewData);
            $this->assertEquals(2,                     $portlet->column);
            $this->assertEquals(5,                     $portlet->position);
            $this->assertEquals('Testing Title',       $portlet->getTitle());
            $this->assertEquals(false,                 $portlet->isEditable());
            $this->assertEquals('Test',                $portlet->layoutId);
            //$this->assertEquals(true,                  $portlet->collapsed); //reenable once working
            $this->assertEquals('ContactsForAccountRelatedList', $portlet->viewType);
            $this->assertEquals($user->id,             $portlet->user->id);
            $view = $portlet->getView();
        }
    }
?>
