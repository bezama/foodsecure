<?php


    /**
     * A  NotificationRules to manage when there is new fosa stable release.
     */
    class NewfosaVersionAvailableNotificationRules extends JobsManagerAccessNotificationRules
    {
        protected $critical    = true;

        public static function getDisplayName()
        {
            return Yii::t('Default', 'new stable fosa release available');
        }

        public static function getType()
        {
            return 'NewfosaVersionAvailable';
        }
    }
?>