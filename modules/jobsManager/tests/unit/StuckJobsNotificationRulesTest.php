<?php


    class StuckJobsNotificationRulesTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            UserTestHelper::createBasicUser('billy');
            UserTestHelper::createBasicUser('sally');
        }

        public function testGetUsers()
        {
            $billy = User::getByUsername('billy');
            $sally = User::getByUsername('sally');
            $rules = new StuckJobsNotificationRules();
            $this->assertEquals(1, count($rules->getUsers())); //super user

            //Now add billy and sally to allow rights to the JobManager
            $billy->setRight('JobsManagerModule', JobsManagerModule::RIGHT_ACCESS_JOBSMANAGER);
            $this->assertTrue($billy->save());
            $sally->setRight('JobsManagerModule', JobsManagerModule::RIGHT_ACCESS_JOBSMANAGER);
            $this->assertTrue($sally->save());

            $billy = User::getByUsername('billy');
            $this->assertEquals(Right::ALLOW,
                    $billy->getEffectiveRight('JobsManagerModule', JobsManagerModule::RIGHT_ACCESS_JOBSMANAGER));

            //Rules should still show 1 since the users are already loaded (isLoaded = true)
            $this->assertEquals(1, count($rules->getUsers()));

            //Instantiate a new rules object.
            $rules = new StuckJobsNotificationRules();
            $this->assertEquals(3, count($rules->getUsers()));
        }
    }
?>
