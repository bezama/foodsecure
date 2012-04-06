<?php


    /**
     * Jobs Manager user interface actions.
     * Walkthrough for the super user of all possible controller actions.
     * Since this is a super user, he should have access to all controller actions
     * without any exceptions being thrown.
     */
    class JobsManagerSuperUserWalkthroughTest extends fosaWalkthroughBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
        }

        public function testSuperUserAllDefaultControllerActions()
        {
            $super = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');

            //Test all default controller actions that do not require any POST/GET variables to be passed.
            //This does not include portlet controller actions.
            $this->runControllerWithNoExceptionsAndGetContent     ('jobsManager/default/');
            $this->runControllerWithNoExceptionsAndGetContent     ('jobsManager/default/list');
        }

        public function testSuperUserResetStuckJobInProcess()
        {
            $super = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');

            //Test when the job is not stuck
            $this->setGetArray(array('type' => 'Monitor'));
            $content = $this->runControllerWithNoExceptionsAndGetContent('jobsManager/default/resetJob');
            $this->assertTrue(strpos($content, 'The job Monitor Job was not found to be stuck and therefore was not reset.') !== false);

            //Test when the job is stuck (Just having a jobInProcess is enough to trigger it.
            $jobInProcess = new JobInProcess();
            $jobInProcess->type = 'Monitor';
            $this->assertTrue($jobInProcess->save());
            $this->setGetArray(array('type' => 'Monitor'));
            $content = $this->runControllerWithNoExceptionsAndGetContent('jobsManager/default/resetJob');
            $this->assertTrue(strpos($content, 'The job Monitor Job has been reset.') !== false);
        }

        public function testSuperUserModalListByType()
        {
            $super = $this->logoutCurrentUserLoginNewUserAndGetByUsername('super');
            $this->setGetArray(array('type' => 'Monitor'));
            $this->runControllerWithNoExceptionsAndGetContent('jobsManager/default/jobLogsModalList');
        }
    }
?>