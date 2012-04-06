<?php


    class JobLogCleanupJobTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function testRun()
        {
            //Create 2 jobLogs, and set one with a date over a week ago (8 days ago) for the endDateTime
            $eightDaysAgoTimestamp = DateTimeUtil::convertTimestampToDbFormatDateTime(time() - (60 * 60 *24 * 8));
            $jobLog                = new JobLog();
            $jobLog->type          = 'Monitor';
            $jobLog->startDateTime = $eightDaysAgoTimestamp;
            $jobLog->endDateTime   = $eightDaysAgoTimestamp;
            $jobLog->status        = JobLog::STATUS_COMPLETE_WITHOUT_ERROR;
            $jobLog->isProcessed = false;
            $jobLog->save();

            $jobLog2                = new JobLog();
            $jobLog2->type          = 'ImportCleanup';
            $jobLog2->startDateTime = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
            $jobLog2->endDateTime   = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
            $jobLog2->status        = JobLog::STATUS_COMPLETE_WITHOUT_ERROR;
            $jobLog2->isProcessed = false;
            $jobLog2->save();

            $job = new JobLogCleanupJob();
            $this->assertTrue($job->run());
            $jobLogs = JobLog::getAll();
            $this->assertEquals(1, count($jobLogs));
            $this->assertEquals($jobLog2->id, $jobLogs[0]->id);
        }
    }
?>