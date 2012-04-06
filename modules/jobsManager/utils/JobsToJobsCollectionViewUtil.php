<?php


    /**
     * A utility for getting information about jobs and putting into an array of data that is useful
     * for the JobsCollectionView.
     */
    class JobsToJobsCollectionViewUtil
    {
        /**
         * Indicates a job is not currently In Process
         */
        const STATUS_NOT_RUNNING         = 1;

        /**
         * Indicates a job is currently In Process and stuck based on it lasting longer than the
         * threshold.
         */
        const STATUS_IN_PROCESS_STUCK    = 2;

        /**
         * Indicates a job is currently In Process
         */
        const STATUS_IN_PROCESS          = 3;

        /**
         * @return array of data for the Monitor job.  Includes information such as the display label,
         * whether it is running or not, and the last completion time.
         */
        public static function getMonitorJobData()
        {
            return self::getJobDataByType('Monitor');
        }

        /**
         * @return array of data for jobs that are not the monitor job.  Includes information such as the display label,
         * whether it is running or not, and the last completion time.
         */
        public static function getNonMonitorJobsData()
        {
            $jobsData       = array();
            $modules = Module::getModuleObjects();
            foreach ($modules as $module)
            {
                $jobsClassNames = $module::getAllClassNamesByPathFolder('jobs');
                foreach ($jobsClassNames as $jobClassName)
                {
                    $classToEvaluate     = new ReflectionClass($jobClassName);
                    if (is_subclass_of($jobClassName, 'BaseJob') && !$classToEvaluate->isAbstract() &&
                        $jobClassName != 'MonitorJob')
                    {
                        $jobsData[$jobClassName::getType()] = self::getJobDataByType($jobClassName::getType());
                    }
                }
            }
            return $jobsData;
        }

        protected static function getJobDataByType($type)
        {
            assert('is_string($type) && $type != ""');
            $jobClassName                           = $type . 'Job';
            $lastCompletedJobLog                    = self::getLastCompletedJobLogByType($type);
            $jobInProcess                           = self::getIfJobIsInProcessOtherwiseReturnNullByType($type);
            $jobData = array();
            $jobData['label']                       = $jobClassName::getDisplayName();
            $jobData['lastCompletedRunContent']     = self::makeLastCompletedRunContentByJobLog($lastCompletedJobLog);
            $jobData['statusContent']               = self::makeStatusContentByJobInProcess($jobInProcess);
            $jobData['status']                      = self::resolveStatusByJobInProcess($jobInProcess);
            $jobData['recommendedFrequencyContent'] = $jobClassName::getRecommendedRunFrequencyContent();
            return $jobData;
        }

        protected static function getIfJobIsInProcessOtherwiseReturnNullByType($type)
        {
            assert('is_string($type) && $type != ""');
            try
            {
                $jobInProcess = JobInProcess::getByType($type);
            }
            catch (NotFoundException $e)
            {
                $jobInProcess = null;
            }
            return $jobInProcess;
        }

        protected static function makeLastCompletedRunContentByJobLog($jobLog)
        {
            assert('$jobLog instanceof JobLog || $jobLog == null');
            if ($jobLog == null)
            {
                return Yii::t('Default', 'Never');
            }
            $content = DateTimeUtil::
                           convertDbFormattedDateTimeToLocaleFormattedDisplay($jobLog->createdDateTime);
            if ($jobLog != null && $jobLog->status == JobLog::STATUS_COMPLETE_WITH_ERROR)
            {
                $content .= ' ' . Yii::t('Default', '[with errors]');
            }
            return $content;
        }

        protected static function makeStatusContentByJobInProcess($jobInProcess)
        {
            assert('$jobInProcess instanceof JobInProcess || $jobInProcess == null');
            if ($jobInProcess != null && JobsManagerUtil::isJobInProcessOverThreashold($jobInProcess, $jobInProcess->type))
            {
                return Yii::t('Default', 'In Process (Stuck)');
            }
            elseif ($jobInProcess != null)
            {
                $startedDateTimeContent = DateTimeUtil::
                                          convertDbFormattedDateTimeToLocaleFormattedDisplay($jobInProcess->createdDateTime);
                return Yii::t('Default', 'In Process [Started: {startedDateTime}]',
                       array('{startedDateTime}' => $startedDateTimeContent));
            }
            else
            {
                return Yii::t('Default', 'Not Running');
            }
        }

        protected static function resolveStatusByJobInProcess($jobInProcess)
        {
            assert('$jobInProcess instanceof JobInProcess || $jobInProcess == null');
            if ($jobInProcess != null && JobsManagerUtil::isJobInProcessOverThreashold($jobInProcess, $jobInProcess->type))
            {
                return self::STATUS_IN_PROCESS_STUCK;
            }
            elseif ($jobInProcess != null)
            {
                return self::STATUS_IN_PROCESS;
            }
            else
            {
                return self::STATUS_NOT_RUNNING;
            }
        }

        protected static function getLastCompletedJobLogByType($type)
        {
            assert('is_string($type) && $type != ""');
            $searchAttributeData = array();
            $searchAttributeData['clauses'] = array(
                1 => array(
                    'attributeName'        => 'type',
                    'operatorType'         => 'equals',
                    'value'                => $type,
                ),
            );
            $searchAttributeData['structure'] = '1';
            $joinTablesAdapter = new RedBeanModelJoinTablesQueryAdapter('JobLog');
            $sort   = RedBeanModelDataProvider::
                      resolveSortAttributeColumnName('JobLog', $joinTablesAdapter, 'createdDateTime');
            $where  = RedBeanModelDataProvider::makeWhere('JobLog', $searchAttributeData, $joinTablesAdapter);
            $models = JobLog::getSubset($joinTablesAdapter, null, 1, $where, $sort . ' desc');
            if (count($models) > 1)
            {
                throw new NotSupportedException();
            }
            if (count($models) == 0)
            {
                return null;
            }
            return $models[0];
        }
    }
?>