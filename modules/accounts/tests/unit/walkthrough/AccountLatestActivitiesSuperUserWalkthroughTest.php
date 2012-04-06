<?php


    /**
     * Account Latest Activities Super User Walkthrough.
     * Walkthrough for the super user of all possible latest activity controller actions.
     * Since this is a super user, he should have access to all controller actions
     * without any exceptions being thrown.
     */
    class AccountLatestActivitiesSuperUserWalkthroughTest extends fosaWalkthroughBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            //Setup test data owned by the super user.
            AccountTestHelper::createAccountByNameForOwner('superAccount', $super);
        }

        public function testSuperUserAllDefaultControllerActions()
        {
            //Set the current user as the super user.
            $super = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');

            $accounts = Account::getAll();
            $this->assertEquals(1, count($accounts));
            $superAccountId = self::getModelIdByModelNameAndName ('Account', 'superAccount');

            //Load Details view to generate the portlets.
            $this->setGetArray(array('id' => $superAccountId));
            $this->resetPostArray();
            $this->runControllerWithNoExceptionsAndGetContent('accounts/default/details');

            //Find the LatestActivity portlet.
            $portletToUse = null;
            $portlets     = Portlet::getAll();
            foreach ($portlets as $portlet)
            {
                if ($portlet->viewType == 'AccountNoteInlineEditAndLatestActivtiesForPortlet')
                {
                    $portletToUse = $portlet;
                    break;
                }
            }
            $this->assertNotNull($portletToUse);
            $this->assertEquals('AccountNoteInlineEditAndLatestActivtiesForPortletView', get_class($portletToUse->getView()));

            //Load the portlet details for latest activity for viewType LatestActivitiesView::VIEW_TYPE_LISTVIEW
            $getData = array('id' => $superAccountId,
                             'portletId' => 2,
                             'uniqueLayoutId' => 'AccountDetailsAndRelationsView_2',
                             'LatestActivitiesConfigurationForm' => array(
                                'filteredByModelName' => 'all',
                                'rollup' => false,
                                'viewType' => LatestActivitiesView::VIEW_TYPE_LISTVIEW,
                             ));
            $this->setGetArray($getData);
            $this->resetPostArray();
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');

            //Now add roll up
            $getData['LatestActivitiesConfigurationForm']['rollup'] = true;
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            //Now filter by meeting, task, and note
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Meeting';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Note';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Task';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            //Now do the same thing with filtering but turn off rollup.
            $getData['LatestActivitiesConfigurationForm']['rollup'] = true;
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Meeting';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Note';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Task';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');

            //Repeat everything above but do the summary view.
            //Load rollup off with SUMMARY view.
            $getData['LatestActivitiesConfigurationForm']['rollup'] = false;
            $getData['LatestActivitiesConfigurationForm']['viewType'] = LatestActivitiesView::VIEW_TYPE_SUMMARY;
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'all';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            //Turn rollup on
            $getData['LatestActivitiesConfigurationForm']['rollup'] = true;
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            //Now filter by meeting, task, and note
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Meeting';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Note';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Task';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            //Now do the same thing with filtering but turn off rollup.
            $getData['LatestActivitiesConfigurationForm']['rollup'] = true;
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Meeting';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Note';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
            $getData['LatestActivitiesConfigurationForm']['filteredByModelName'] = 'Task';
            $this->setGetArray($getData);
            $content = $this->runControllerWithNoExceptionsAndGetContent('accounts/defaultPortlet/details');
        }
    }
?>