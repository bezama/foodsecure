<?php


    /**
     * A job for checking if there is newer stable version of fosa.
     */
    class CheckfosaUpdatesJob extends BaseJob
    {
        /**
         * @returns Translated label that describes this job type.
         */
        public static function getDisplayName()
        {
           return Yii::t('Default', 'Check if there is newer fosa version available Job');
        }

        /**
         * @return The type of the NotificationRules
         */
        public static function getType()
        {
            return 'CheckfosaUpdates';
        }

        public static function getRecommendedRunFrequencyContent()
        {
            return Yii::t('Default', 'Once a week, early in the morning.');
        }

        /**
         * Check if there are new fosa updates.
         * Then delete these logs.
         *
         * @see BaseJob::run()
         */
        public function run()
        {
            fosaModule::checkAndUpdatefosaInfo(true);
            return true;
        }
    }
?>