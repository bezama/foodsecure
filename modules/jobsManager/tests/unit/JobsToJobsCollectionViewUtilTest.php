<?php


    class JobsToJobsCollectionViewUtilTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function testGetMonitorJobData()
        {
            $data = JobsToJobsCollectionViewUtil::getMonitorJobData();
            $this->assertEquals('Monitor Job', $data['label']);
        }

        public function testGetNonMonitorJobsData()
        {
            $jobsData = JobsToJobsCollectionViewUtil::getNonMonitorJobsData();
            $this->assertTrue(count($jobsData) > 1);
            $this->assertTrue(!isset($jobsData['Monitor']));
            $this->assertTrue(isset($jobsData['ImportCleanup']));
            $this->assertTrue(isset($jobsData['CurrencyRatesUpdate']));
        }
    }
?>
