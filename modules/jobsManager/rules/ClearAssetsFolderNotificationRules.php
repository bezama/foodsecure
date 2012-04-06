<?php


    /**
     * Inform user(super admin) to clear assets folder, after running updateSchema command.
     */
    class ClearAssetsFolderNotificationRules extends JobsManagerAccessNotificationRules
    {
        protected $critical    = false;

        public static function getDisplayName()
        {
            return Yii::t('Default', 'Clear the assets folder on server(optional).');
        }

        public static function getType()
        {
            return 'ClearAssetsFolder';
        }
    }
?>