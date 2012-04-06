<?php


    /**
     * A  NotificationRules to manage when jobs are detected as being 'stuck' by the
     * job monitor.
     */
    class StuckJobsNotificationRules extends JobsManagerAccessNotificationRules
    {
        protected $critical    = true;

        public static function getDisplayName()
        {
            return Yii::t('Default', 'Scheduled jobs are stuck');
        }

        public static function getType()
        {
            return 'StuckJobs';
        }
    }
?>