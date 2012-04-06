<?php


    /**
     * A job for removing old job logs.
     */
    class JobLogCleanupJob extends BaseJob
    {
        /**
         * @returns Translated label that describes this job type.
         */
        public static function getDisplayName()
        {
           return Yii::t('Default', 'Cleanup old job logs Job');
        }

        /**
         * @return The type of the NotificationRules
         */
        public static function getType()
        {
            return 'JobLogCleanup';
        }

        public static function getRecommendedRunFrequencyContent()
        {
            return Yii::t('Default', 'Once a week, early in the morning.');
        }

        /**
         * Return all job logs where the modifiedDateTime was more than 1 week ago.
         * Then delete these logs.
         *
         * @see BaseJob::run()
         */
        public function run()
        {
            $oneWeekAgoTimeStamp = DateTimeUtil::convertTimestampToDbFormatDateTime(time() - 60 * 60 *24 * 7);
            $searchAttributeData = array();
            $searchAttributeData['clauses'] = array(
                1 => array(
                    'attributeName'        => 'endDateTime',
                    'operatorType'         => 'lessThan',
                    'value'                => $oneWeekAgoTimeStamp,
                ),
            );
            $searchAttributeData['structure'] = '1';
            $joinTablesAdapter = new RedBeanModelJoinTablesQueryAdapter('JobLog');
            $where = RedBeanModelDataProvider::makeWhere('JobLog', $searchAttributeData, $joinTablesAdapter);
            $jobLogModels = JobLog::getSubset($joinTablesAdapter, null, 1000, $where, null);
            foreach ($jobLogModels as $jobLog)
            {
                $jobLog->delete();
            }
            return true;
        }
    }
?>