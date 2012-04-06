<?php


    /**
     * Base class for making Jobs.  Jobs can be run on a scheduled basis.  An example job would be a job
     * that removes old import tables.
     */
    abstract class BaseJob
    {
        /**
         * Populated when the job runs if needed.
         * @var string
         */
        protected $errorMessage;

        /**
         * @var mixed null or instance of MessageLogger
         */
        private $messageLogger;

        /**
         * After a Job is instantiated, the run method is called to execute the job.
         */
        abstract public function run();

        /**
         * @returns Translated label that describes this job type.
         */
        public static function getDisplayName()
        {
            throw new NotImplementedException();
        }

        /**
         * @return The type of the NotificationRules
         */
        public static function getType()
        {
            throw new NotImplementedException();
        }

        /**
         * @return string content specifying how often this job should be run as a scheduled task.
         */
        public static function getRecommendedRunFrequencyContent()
        {
            throw new NotImplementedException();
        }

        /**
         * @returns error message string otherwise returns null if not populated.
         */
        public function getErrorMessage()
        {
            return $this->errorMessage;
        }

        /**
         * @returns the threshold for how long a job is allowed to run. This is the 'threshold'. If a job
         * is running longer than the threshold, the monitor job might take action on it since it would be
         * considered 'stuck'.
         */
        public static function getRunTimeThresholdInSeconds()
        {
            return 60;
        }

        public function setMessageLogger(MessageLogger $messageLogger)
        {
            $this->messageLogger = $messageLogger;
        }

        public function getMessageLogger()
        {
            if ($this->messageLogger == null)
            {
                $this->messageLogger = new MessageLogger();
            }
            return $this->messageLogger;
        }
    }
?>