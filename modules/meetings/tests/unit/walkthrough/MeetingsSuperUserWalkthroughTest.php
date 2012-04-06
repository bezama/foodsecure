<?php


    /**
     * Meeting module walkthrough tests for a super user.
     */
    class MeetingsSuperUserWalkthroughTest extends fosaWalkthroughBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            //Setup test data owned by the super user.
            $account = AccountTestHelper::createAccountByNameForOwner('superAccount', $super);
            AccountTestHelper::createAccountByNameForOwner('superAccount2', $super);
            ContactTestHelper::createContactWithAccountByNameForOwner('superContact', $super, $account);
            ContactTestHelper::createContactWithAccountByNameForOwner('superContact2', $super, $account);
            ContactTestHelper::createContactWithAccountByNameForOwner('superContact3', $super, $account);
        }

        public function testSuperUserAllDefaultControllerActions()
        {
            $super = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');

            $superAccountId  = self::getModelIdByModelNameAndName ('Account', 'superAccount');
            $superAccountId2 = self::getModelIdByModelNameAndName ('Account', 'superAccount2');
            $superContactId  = self::getModelIdByModelNameAndName ('Contact', 'superContact superContactson');
            $superContactId2  = self::getModelIdByModelNameAndName ('Contact', 'superContact2 superContact2son');
            $superContactId3  = self::getModelIdByModelNameAndName ('Contact', 'superContact3 superContact3son');
            $account  = Account::getById($superAccountId);
            $account2 = Account::getById($superAccountId2);
            $contact  = Contact::getById($superContactId);
            $contact2  = Contact::getById($superContactId2);
            $contact3  = Contact::getById($superContactId3);

            //confirm no existing activities exist
            $activities = Activity::getAll();
            $this->assertEquals(0, count($activities));

            //Test just going to the create from relation view.
            $this->setGetArray(array(   'relationAttributeName' => 'Account', 'relationModelId' => $superAccountId,
                                        'relationModuleId'      => 'accounts', 'redirectUrl' => 'someRedirect'));
            $this->runControllerWithNoExceptionsAndGetContent('meetings/default/createFromRelation');

            //add related meeting for account using createFromRelation action
            $activityItemPostData = array('Account' => array('id' => $superAccountId));
            $this->setGetArray(array(   'relationAttributeName' => 'Account', 'relationModelId' => $superAccountId,
                                        'relationModuleId'      => 'accounts', 'redirectUrl' => 'someRedirect'));
            $this->setPostArray(array('ActivityItemForm' => $activityItemPostData,
                                      'Meeting' => array('name' => 'myMeeting', 'startDateTime' => '11/1/11 7:45 PM')));
            $this->runControllerWithRedirectExceptionAndGetContent('meetings/default/createFromRelation');

            //now test that the new meeting exists, and is related to the account.
            $meetings = Meeting::getAll();
            $this->assertEquals(1, count($meetings));
            $this->assertEquals('myMeeting', $meetings[0]->name);
            $this->assertEquals(1, $meetings[0]->activityItems->count());
            $activityItem1 = $meetings[0]->activityItems->offsetGet(0);
            $this->assertEquals($account, $activityItem1);

            //test viewing the existing meeting in a details view
            $this->setGetArray(array('id' => $meetings[0]->id));
            $this->resetPostArray();
            $this->runControllerWithNoExceptionsAndGetContent('meetings/default/details');

            //test editing an existing meeting and saving. Add a second relation, to a contact.
            //First just go to the edit view and confirm it loads ok.
            $this->setGetArray(array('id' => $meetings[0]->id, 'redirectUrl' => 'someRedirect'));
            $this->resetPostArray();
            $this->runControllerWithNoExceptionsAndGetContent('meetings/default/edit');
            //Save changes via edit action.
            $activityItemPostData = array(  'Account' => array('id' => $superAccountId), 'Contact' => array('id' => $superContactId));
            $this->setGetArray(array('id' => $meetings[0]->id, 'redirectUrl' => 'someRedirect'));
            $this->setPostArray(array('ActivityItemForm' => $activityItemPostData, 'Meeting' => array('name' => 'myMeetingX')));
            $this->runControllerWithRedirectExceptionAndGetContent('meetings/default/edit');
            //Confirm changes applied correctly.
            $meetings = Meeting::getAll();
            $this->assertEquals(1, count($meetings));
            $this->assertEquals('myMeetingX', $meetings[0]->name);
            $this->assertEquals(2, $meetings[0]->activityItems->count());
            $activityItem1 = $meetings[0]->activityItems->offsetGet(0);
            $activityItem2 = $meetings[0]->activityItems->offsetGet(1);
            $this->assertEquals($account, $activityItem1);
            $this->assertEquals($contact, $activityItem2);

            //Remove contact relation.  Switch account relation to a different account.
            $activityItemPostData = array('Account' => array('id' => $superAccountId2));
            $this->setGetArray(array('id' => $meetings[0]->id));
            $this->setPostArray(array('ActivityItemForm' => $activityItemPostData, 'Meeting' => array('name' => 'myMeetingX')));
            $this->runControllerWithRedirectExceptionAndGetContent('meetings/default/edit');
            //Confirm changes applied correctly.
            $meetings = Meeting::getAll();
            $this->assertEquals(1, count($meetings));
            $this->assertEquals('myMeetingX', $meetings[0]->name);
            $this->assertEquals(1, $meetings[0]->activityItems->count());
            $activityItem1 = $meetings[0]->activityItems->offsetGet(0);
            $this->assertEquals($account2, $activityItem1);

            //test removing a meeting.
            $this->setGetArray(array('id' => $meetings[0]->id));
            $this->resetPostArray();
            $this->runControllerWithRedirectExceptionAndGetContent('meetings/default/delete');
            //Confirm no more meetings exist.
            $meetings = Meeting::getAll();
            $this->assertEquals(0, count($meetings));

            //Test adding a meeting with multiple contacts
            $activityItemPostData = array('Account' => array('id' => $superAccountId),
                                          'Contact' => array('ids' =>
                                                $superContactId . ',' . $superContactId2 . ',' . $superContactId3)); // Not Coding Standard
            $this->setGetArray(array(   'relationAttributeName' => 'Account', 'relationModelId' => $superAccountId,
                                        'relationModuleId'      => 'accounts', 'redirectUrl' => 'someRedirect'));
            $this->setPostArray(array('ActivityItemForm' => $activityItemPostData,
                                      'Meeting' => array('name' => 'myMeeting2', 'startDateTime' => '11/1/11 7:45 PM')));
            $this->runControllerWithRedirectExceptionAndGetContent('meetings/default/createFromRelation');

            //now test that the new meeting exists, and is related to the account.
            $meetings = Meeting::getAll();
            $this->assertEquals(1, count($meetings));
            $this->assertEquals('myMeeting2', $meetings[0]->name);
            $this->assertEquals(4, $meetings[0]->activityItems->count());
            $activityItem1 = $meetings[0]->activityItems->offsetGet(0);
            $this->assertEquals($account, $activityItem1);
        }
    }
?>