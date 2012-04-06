<?php


    /**
     * A job for monitoring all other jobs and making sure they are functioning properly.
     */
    class MonitorJob extends BaseJob
    {
        /**
         * @returns Translated label that describes this job type.
         */
        public static function getDisplayName()
        {
           return Yii::t('Default', 'Monitor Job');
        }

        /**
         * @return The type of the NotificationRules
         */
        public static function getType()
        {
            return 'Monitor';
        }

        public static function getRecommendedRunFrequencyContent()
        {
            return Yii::t('Default', 'Every 5 minutes');
        }

        /**
         * @returns translated string to use when communicating that the monitor is stuck.
         */
        public static function getStuckStringContent()
        {
            return Yii::t('Default', 'The monitor job is stuck.');
        }

        public function run()
        {
            $jobsInProcess = static::getNonMonitorJobsInProcessModels();
            foreach ($jobsInProcess as $jobInProcess)
            {
                if (JobsManagerUtil::isJobInProcessOverThreashold($jobInProcess, $jobInProcess->type))
                {
                    $message                    = new NotificationMessage();
                    $message->textContent       = Yii::t('Default', 'The system has detected there are jobs that are stuck.');
                    $rules                      = new StuckJobsNotificationRules();
                    NotificationsUtil::submit($message, $rules);
                }
            }
            $jobLogs = static::getNonMonitorJobLogsUnprocessed();
            foreach ($jobLogs as $jobLog)
            {
                if ($jobLog->status == JobLog::STATUS_COMPLETE_WITH_ERROR)
                {
                    $message                      = new NotificationMessage();
                    $message->htmlContent         = Yii::t('Default', 'Job completed with errors.');
                    $url                          = Yii::app()->createAbsoluteUrl('jobsManager/default/jobLogDetails/',
                                                                        array('id' => $jobLog->id));
                    $message->htmlContent        .= "<br/>" . CHtml::link(Yii::t('Default', 'Click Here'), $url);
                    $rules                        = new JobCompletedWithErrorsNotificationRules();
                    NotificationsUtil::submit($message, $rules);
                }
                $jobLog->isProcessed         = true;
                $jobLog->save();
            }
            return true;
        }

        protected static function getNonMonitorJobsInProcessModels()
        {
            $searchAttributeData = array();
            $searchAttributeData['clauses'] = array(
                1 => array(
                    'attributeName'        => 'type',
                    'operatorType'         => 'doesNotEqual',
                    'value'                => 'Monitor',
                ),
            );
            $searchAttributeData['structure'] = '1';
            $joinTablesAdapter = new RedBeanModelJoinTablesQueryAdapter('JobInProcess');
            $where = RedBeanModelDataProvider::makeWhere('JobInProcess', $searchAttributeData, $joinTablesAdapter);
            return JobInProcess::getSubset($joinTablesAdapter, null, null, $where, null);
        }

        protected static function getNonMonitorJobLogsUnprocessed()
        {
            $searchAttributeData = array();
            $searchAttributeData['clauses'] = array(
                1 => array(
                    'attributeName'        => 'type',
                    'operatorType'         => 'doesNotEqual',
                    'value'                => 'Monitor',
                ),
                2 => array(
                    'attributeName'        => 'isProcessed',
                    'operatorType'         => 'doesNotEqual',
                    'value'                => (bool)1,
                ),
            );
            $searchAttributeData['structure'] = '1 and 2';
            $joinTablesAdapter = new RedBeanModelJoinTablesQueryAdapter('JobLog');
            $where = RedBeanModelDataProvider::makeWhere('JobLog', $searchAttributeData, $joinTablesAdapter);
            return JobLog::getSubset($joinTablesAdapter, null, null, $where, null);
        }
    }
?>