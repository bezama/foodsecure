<?php


    /**
    * Testing creating mixed attributes (multi-select and tag cloud), while already having an existing account
    * and making sure things work correctly with retrieval and save.
    */
    class AccountsSuperUserMixedAttributeCreationWalkthroughTest extends fosaWalkthroughBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super                      = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
        }

        /**
         * @see MissingBeanException (Related to why we needed this test in the first place.)  Since superAccount
         * is created before the attributes, was causing a problem where some parts of the relatedModel beans are not
         * present.  Catching the MissingBeanException in the RedBeanModel solved this problem.
         */
        public function testSuperUserSavingAccountCreatedBeforeThreeoRequiredCustomAttributesAreCreated()
        {
            $super = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');

            //First create an account before you create multiselect and tagcloud attributes
            $account = AccountTestHelper::createAccountByNameForOwner('superAccount', $super);
            $account->forget();

            //Test create field list.
            $this->resetPostArray();
            $this->setGetArray(array('moduleClassName' => 'AccountsModule'));
            $this->runControllerWithNoExceptionsAndGetContent('designer/default/attributeCreate');

            //Create 2 custom attributes that are required.
            $this->createDropDownCustomFieldByModule            ('AccountsModule', 'dropdown');
            $this->createMultiSelectDropDownCustomFieldByModule ('AccountsModule', 'multiselect');
            $this->createTagCloudCustomFieldByModule            ('AccountsModule', 'tagcloud');

            //Save the account again.  Everything is fine.
            $account       = Account::getByName('superAccount');

            $account[0]->save(false);
            $account[0]->forget();

            //Retrieving the account again at this point should retrieve ok.
            $account       = Account::getByName('superAccount');
            $account[0]->save(false);
            $account[0]->forget();
        }
    }
?>
