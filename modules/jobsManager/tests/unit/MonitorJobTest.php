<?php


    class MonitorJobTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            Yii::import('application.modules.jobsManager.tests.unit.jobs.*');
        }

        public function testRunAndProcessStuckJobs()
        {
            Yii::app()->user->userModel               = User::getByUsername('super');
            $emailAddress                             = new Email();
            $emailAddress->emailAddress               = 'sometest@fosaalerts.com';
            Yii::app()->user->userModel->primaryEmail = $emailAddress;
            $saved                                    = Yii::app()->user->userModel->save();
            $this->assertTrue($saved);

            $this->assertEquals(0, Yii::app()->emailHelper->getQueuedCount());
            $this->assertEquals(0, Yii::app()->emailHelper->getSentCount());

            $monitorJob = new MonitorJob();
            $this->assertEquals(0, count(JobInProcess::getAll()));
            $this->assertEquals(0, count(Notification::getAll()));
            $jobInProcess = new JobInProcess();
            $jobInProcess->type = 'Test';
            $this->assertTrue($jobInProcess->save());
            //Should make createdDateTime long enough in past to trigger as stuck.
            $createdDateTime = DateTimeUtil::convertTimestampToDbFormatDateTime(time() - 1000);
            $sql = "Update item set createddatetime = '" . $createdDateTime . "' where id = " .
                   $jobInProcess->getClassId('Item');
            R::exec($sql);
            $jobInProcess->forget();
            $monitorJob->run();
            $this->assertEquals(1, count(Notification::getAll()));

            //Confirm an email was sent
            $this->assertEquals(0, Yii::app()->emailHelper->getQueuedCount());
            $this->assertEquals(1, Yii::app()->emailHelper->getSentCount());
        }
    }
?>