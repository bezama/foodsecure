<?php


    /**
     * A  NotificationRules to manage when jobs are detected as being 'stuck' by the
     * job monitor.
     */
    class HostInfoAndScriptUrlNotSetupNotificationRules extends JobsManagerAccessNotificationRules
    {
        protected $critical    = true;

        public static function getDisplayName()
        {
            return Yii::t('Default', 'hostInfo or scriptUrl not set up');
        }

        public static function getType()
        {
            return 'HostInfoAndScriptUrlNotSetup';
        }
    }
?>