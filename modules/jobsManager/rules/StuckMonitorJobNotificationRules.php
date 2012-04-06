<?php


    /**
     * A  NotificationRules to manage when the monitor job itself are detected as being 'stuck'.
     */
    class StuckMonitorJobNotificationRules extends JobsManagerAccessNotificationRules
    {
        protected $critical    = true;

        public static function getDisplayName()
        {
            return Yii::t('Default', 'The monitor job is stuck.');
        }

        public static function getType()
        {
            return 'StuckMonitorJob';
        }
    }
?>