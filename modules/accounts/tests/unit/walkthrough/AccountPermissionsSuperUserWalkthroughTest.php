<?php


    /**
     * Account Permissions Super User Walkthrough.
     * Walkthrough for the super user of all possible permissions scenarios. Primarily focuses on changing the
     * DerivedExplicitReadWriteModelPermissions element values.
     */
    class AccountPermissionsSuperUserWalkthroughTest extends fosaWalkthroughBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            $everyoneGroup = Group::getByName(Group::EVERYONE_GROUP_NAME);
            assert($everyoneGroup->save()); // Not Coding Standard

            $group1        = new Group();
            $group1->name  = 'Group1';
            assert($group1->save()); // Not Coding Standard

            //Setup test data owned by the super user.
            AccountTestHelper::createAccountByNameForOwner('superAccount', $super);
        }

        public function testSuperUserDerivedExplicitReadWriteModelPermissionsEditExistingAccount()
        {
            //Set the current user as the super user.
            $super          = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');

            $accounts = Account::getAll();
            $this->assertEquals(1, count($accounts[0]));
            $this->assertEquals(0, count($accounts[0]->permissions));
            $superAccountId = self::getModelIdByModelNameAndName ('Account', 'superAccount');
            $group1         = Group::getByName('Group1');
            $everyoneGroup  = Group::getByName(Group::EVERYONE_GROUP_NAME);

            //Save account and add a non-everyone group permission.
            //Permissions is the only thing changing on the account.
            $this->setGetArray(array('id' => $superAccountId));
            $postData = array('type' => ExplicitReadWriteModelPermissionsUtil::MIXED_TYPE_NONEVERYONE_GROUP,
                  'nonEveryoneGroup' => $group1->id);
            $this->setPostArray(array('Account' =>
                array('explicitReadWriteModelPermissions' => $postData)));
            //Make sure the redirect is to the details view and not the list view.
            $this->runControllerWithRedirectExceptionAndGetContent('accounts/default/edit',
                        Yii::app()->createUrl('accounts/default/details', array('id' => $superAccountId)));
            //Confirm the permissions are set right based on how the account was saved.
            $accounts[0]->forget();
            $explicitReadWriteModelPermissions = ExplicitReadWriteModelPermissionsUtil::
                                                 makeBySecurableItem(Account::getById($superAccountId));
            $readWritePermitables = $explicitReadWriteModelPermissions->getReadWritePermitables();
            $readOnlyPermitables  = $explicitReadWriteModelPermissions->getReadOnlyPermitables();
            $this->assertEquals(1, count($readWritePermitables));
            $this->assertEquals(0, count($readOnlyPermitables));
            $this->assertEquals($group1, $readWritePermitables[$group1->id]);

            //Change the permissions to Everyone group
            $this->setGetArray(array('id' => $superAccountId));
            $postData = array('type' => ExplicitReadWriteModelPermissionsUtil::MIXED_TYPE_EVERYONE_GROUP);
            $this->setPostArray(array('Account' =>
                array('explicitReadWriteModelPermissions' => $postData)));
            //Make sure the redirect is to the details view and not the list view.
            $this->runControllerWithRedirectExceptionAndGetContent('accounts/default/edit',
                        Yii::app()->createUrl('accounts/default/details', array('id' => $superAccountId)));
            //Confirm the permissions are set right based on how the account was saved.
            $accounts[0]->forget();
            $explicitReadWriteModelPermissions = ExplicitReadWriteModelPermissionsUtil::
                                                 makeBySecurableItem(Account::getById($superAccountId));
            $readWritePermitables = $explicitReadWriteModelPermissions->getReadWritePermitables();
            $readOnlyPermitables  = $explicitReadWriteModelPermissions->getReadOnlyPermitables();
            $this->assertEquals(1, count($readWritePermitables));
            $this->assertEquals(0, count($readOnlyPermitables));
            $this->assertEquals($everyoneGroup, $readWritePermitables[$everyoneGroup->id]);

            //Remove all explicit permissions.
            $this->setGetArray(array('id' => $superAccountId));
            $postData = array('type' => null);
            $this->setPostArray(array('Account' =>
                array('explicitReadWriteModelPermissions' => $postData)));
            //Make sure the redirect is to the details view and not the list view.
            $this->runControllerWithRedirectExceptionAndGetContent('accounts/default/edit',
                        Yii::app()->createUrl('accounts/default/details', array('id' => $superAccountId)));
            //Confirm the permissions are set right based on how the account was saved.
            $accounts[0]->forget();
            $explicitReadWriteModelPermissions = ExplicitReadWriteModelPermissionsUtil::
                                                 makeBySecurableItem(Account::getById($superAccountId));
            $readWritePermitables = $explicitReadWriteModelPermissions->getReadWritePermitables();
            $readOnlyPermitables  = $explicitReadWriteModelPermissions->getReadOnlyPermitables();
            $this->assertEquals(0, count($readWritePermitables));
            $this->assertEquals(0, count($readOnlyPermitables));
        }

        public function testSuperUserDerivedExplicitReadWriteModelPermissionsCreateNewAccounts()
        {
            //Set the current user as the super user.
            $super          = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $group1         = Group::getByName('Group1');
            $everyoneGroup  = Group::getByName(Group::EVERYONE_GROUP_NAME);

            //Create a new account with no explicit permissions
            $this->resetGetArray();
            $postData = array('type' => null);
            $this->setPostArray(array('Account' => array(
                                            'name'        => 'myNewAccount',
                                            'officePhone' => '456765421',
                                            'explicitReadWriteModelPermissions' => $postData)));
            //Make sure the redirect is to the details view and not the list view.
            $this->runControllerWithRedirectExceptionAndGetContent('accounts/default/create'); // Not Coding Standard
            //Confirm the permissions are set right based on how the account was saved.
                        $accounts = Account::getByName('myNewAccount');
            $this->assertEquals(1, count($accounts));
            $accountId = $accounts[0]->id;
            $explicitReadWriteModelPermissions = ExplicitReadWriteModelPermissionsUtil::
                                                 makeBySecurableItem(Account::getById($accountId));
            $readWritePermitables = $explicitReadWriteModelPermissions->getReadWritePermitables();
            $readOnlyPermitables  = $explicitReadWriteModelPermissions->getReadOnlyPermitables();
            $this->assertEquals(0, count($readWritePermitables));
            $this->assertEquals(0, count($readOnlyPermitables));

            //Create a new account with the everyone group explicitly added.
            $this->resetGetArray();
            $postData = array('type' => ExplicitReadWriteModelPermissionsUtil::MIXED_TYPE_EVERYONE_GROUP);
            $this->setPostArray(array('Account' => array(
                                            'name'        => 'myNewAccount2',
                                            'officePhone' => '456765421',
                                            'explicitReadWriteModelPermissions' => $postData)));
            //Make sure the redirect is to the details view and not the list view.
            $this->runControllerWithRedirectExceptionAndGetContent('accounts/default/create'); // Not Coding Standard
            //Confirm the permissions are set right based on how the account was saved.
                        $accounts = Account::getByName('myNewAccount2');
            $this->assertEquals(1, count($accounts));
            $accountId = $accounts[0]->id;
            $explicitReadWriteModelPermissions = ExplicitReadWriteModelPermissionsUtil::
                                                 makeBySecurableItem(Account::getById($accountId));
            $readWritePermitables = $explicitReadWriteModelPermissions->getReadWritePermitables();
            $readOnlyPermitables  = $explicitReadWriteModelPermissions->getReadOnlyPermitables();
            $this->assertEquals(1, count($readWritePermitables));
            $this->assertEquals(0, count($readOnlyPermitables));
            $this->assertEquals($everyoneGroup, $readWritePermitables[$everyoneGroup->id]);

            //Create a new account with a non-everyone group explicitly added.
            $this->resetGetArray();
            $postData = array('type' => ExplicitReadWriteModelPermissionsUtil::MIXED_TYPE_NONEVERYONE_GROUP,
                  'nonEveryoneGroup' => $group1->id);
            $this->setPostArray(array('Account' => array(
                                            'name'        => 'myNewAccount3',
                                            'officePhone' => '456765421',
                                            'explicitReadWriteModelPermissions' => $postData)));
            //Make sure the redirect is to the details view and not the list view.
            $this->runControllerWithRedirectExceptionAndGetContent('accounts/default/create'); // Not Coding Standard
            //Confirm the permissions are set right based on how the account was saved.
                        $accounts = Account::getByName('myNewAccount3');
            $this->assertEquals(1, count($accounts));
            $accountId = $accounts[0]->id;
            $explicitReadWriteModelPermissions = ExplicitReadWriteModelPermissionsUtil::
                                                 makeBySecurableItem(Account::getById($accountId));
            $readWritePermitables = $explicitReadWriteModelPermissions->getReadWritePermitables();
            $readOnlyPermitables  = $explicitReadWriteModelPermissions->getReadOnlyPermitables();
            $this->assertEquals(1, count($readWritePermitables));
            $this->assertEquals(0, count($readOnlyPermitables));
            $this->assertEquals($group1, $readWritePermitables[$group1->id]);
        }
    }
?>