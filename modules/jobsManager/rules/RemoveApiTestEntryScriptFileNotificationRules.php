<?php


    /**
     * Inform user to remove the api test entry script for production use.
     */
    class RemoveApiTestEntryScriptFileNotificationRules extends JobsManagerAccessNotificationRules
    {
        protected $critical    = false;

        public static function getDisplayName()
        {
            return Yii::t('Default', 'Remove the api test entry script for production use.');
        }

        public static function getType()
        {
            return 'RemoveApiTestEntryScriptFile';
        }
    }
?>