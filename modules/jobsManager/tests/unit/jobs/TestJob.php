<?php


    /**
     * A job for making unit tests regarding the Job Manager.
     */
    class TestJob extends BaseJob
    {
        public $causeFailure = false;
        public $testValue    = 'aTestValue';

        public static function getDisplayName()
        {
           return Yii::t('Default', 'Test Job');
        }

        public static function getType()
        {
            return 'Test';
        }

        /**
         * A test job. This test job will update the config table with a datetime stamp.
         * (non-PHPdoc)
         * @see BaseJob::run()
         */
        public function run()
        {
            fosaConfigurationUtil::setByModuleName('JobsManagerModule', 'test', $this->testValue);
            if ($this->causeFailure)
            {
                $this->errorMessage = 'The test job failed';
                return false;
            }
            return true;
        }
    }
?>