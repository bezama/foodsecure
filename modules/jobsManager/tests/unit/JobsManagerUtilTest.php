<?php


    class JobsManagerUtilTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            Yii::import('application.modules.jobsManager.tests.unit.jobs.*');
        }

        public function testRunNonMonitorJob()
        {
            Yii::app()->user->userModel = User::getByUsername('super');
            //Test running a TestJob that it creates a JobLog and does not leave a JobInProcess
            $this->assertEquals(0, count(JobInProcess::getAll()));
            $this->assertEquals(0, count(JobLog::getAll()));

            JobsManagerUtil::runNonMonitorJob('Test', new MessageLogger());
            $this->assertEquals(0, count(JobInProcess::getAll()));
            $jobLogs = JobLog::getAll();
            $this->assertEquals(1, count($jobLogs));
            $this->assertEquals('Test', $jobLogs[0]->type);
            $this->assertEquals(JobLog::STATUS_COMPLETE_WITHOUT_ERROR, $jobLogs[0]->status);
            $this->assertEquals(0, $jobLogs[0]->isProcessed);

            //Now test a job that always fails
            JobsManagerUtil::runNonMonitorJob('TestAlwaysFails', new MessageLogger());
            $this->assertEquals(0, count(JobInProcess::getAll()));
            $jobLogs = JobLog::getAll();
            $this->assertEquals(2, count($jobLogs));
            $this->assertEquals('TestAlwaysFails', $jobLogs[1]->type);
            $this->assertEquals(JobLog::STATUS_COMPLETE_WITH_ERROR, $jobLogs[1]->status);
            $this->assertEquals('The test job failed', $jobLogs[1]->message);
            $this->assertEquals(0, $jobLogs[1]->isProcessed);
        }

        public function testIsJobInProcessOverThreashold()
        {
            Yii::app()->user->userModel = User::getByUsername('super');
            $jobInProcess          = new JobInProcess();
            $jobInProcess->type    = 'Test';
            $this->assertTrue($jobInProcess->save());
            //Set the createdDateTime as way in the past, so that it is over the threshold
            $sql  = "update " . Item::getTableName('Item'). " set createddatetime = '1980-06-03 18:33:03' where id = " .
                    $jobInProcess->getClassId('Item');
            R::exec($sql);
            $jobInProcessId        = $jobInProcess->id;
            $jobInProcess->forget();
            $jobInProcess = JobInProcess::getById($jobInProcessId);
            $this->assertTrue(JobsManagerUtil::isJobInProcessOverThreashold($jobInProcess, $jobInProcess->type));
            $jobInProcess->delete();

            //Test when a job is not over the threshold.
            $jobInProcess          = new JobInProcess();
            $jobInProcess->type    = 'Test';
            $this->assertTrue($jobInProcess->save());
            $this->assertFalse(JobsManagerUtil::isJobInProcessOverThreashold($jobInProcess, $jobInProcess->type));
            $jobInProcess->delete();
        }

        public function testRunMonitorJob()
        {
            Yii::app()->user->userModel = User::getByUsername('super');
            foreach (JobLog::getAll() as $jobLog)
            {
                $jobLog->delete();
            }
            JobsManagerUtil::runNonMonitorJob('Test', new MessageLogger());
            $jobLogs = JobLog::getAll();
            $this->assertEquals(1, count($jobLogs));
            $this->assertEquals(0, $jobLogs[0]->isProcessed);
            $jobLogId = $jobLogs[0]->id;
            JobsManagerUtil::runMonitorJob(new MessageLogger());
            $jobLogs = JobLog::getAll();
            $this->assertEquals(2, count($jobLogs));
            $this->assertEquals($jobLogId, $jobLogs[0]->id);
            $this->assertEquals(1, $jobLogs[0]->isProcessed);
        }
    }
?>
