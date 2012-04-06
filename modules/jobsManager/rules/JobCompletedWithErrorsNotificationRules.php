<?php


    /**
     * A  NotificationRules to manage when the monitor job itself are detected as being 'stuck'.
     */
    class JobCompletedWithErrorsNotificationRules extends JobsManagerAccessNotificationRules
    {
        public static function getDisplayName()
        {
            return Yii::t('Default', 'A job was completed with errors.');
        }

        public static function getType()
        {
            return 'JobCompletedWithErrors';
        }

        public function allowDuplicates()
        {
            return true;
        }
    }
?>