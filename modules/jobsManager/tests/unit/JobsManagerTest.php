<?php


    class JobsManagerTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function testJobInProcess()
        {
            Yii::app()->user->userModel = User::getByUsername('super');
            $jobInProcess          = new JobInProcess();
            $jobInProcess->type    = 'Monitor';
            $this->assertTrue($jobInProcess->save());

            $id = $jobInProcess->id;

            try
            {
                $jobInProcess = JobInProcess::getByType('SomethingElse');
                $this->fail();
            }
            catch (NotFoundException $e)
            {
                //nothing. passes.
            }
            $jobInProcess = JobInProcess::getByType('Monitor');
            $this->assertEquals(1, count($jobInProcess));
            $this->assertEquals($id, $jobInProcess->id);
            $jobInProcess->delete();
            $this->assertEquals(0, count(JobInProcess::getAll()));
        }

        public function testJobLog()
        {
            Yii::app()->user->userModel = User::getByUsername('super');
            $jobLog                = new JobLog();
            $jobLog->type          = 'Monitor';
            $jobLog->startDateTime = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
            $jobLog->endDateTime   = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
            $jobLog->status        = JobLog::STATUS_COMPLETE_WITHOUT_ERROR;

            //Should fail to save because isProcessed is not specified.
            $this->assertFalse($jobLog->save());
            $jobLog->isProcessed = false;
            $this->assertTrue($jobLog->save());
            $id = $jobLog->id;
            $jobLog = JobLog::getById($id);
            $jobLog->delete();
            $this->assertEquals(0, count(JobInProcess::getAll()));
        }
    }
?>
