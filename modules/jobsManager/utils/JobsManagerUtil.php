<?php


    /**
     * A helper class for running normal jobs or the monitor job.
     */
    class JobsManagerUtil
    {
        /**
         * @see JobManagerCommand.  This method is called from the JobManagerCommand which is a commandline
         * tool to run jobs.  Based on the 'type' specified this method will call to run the monitor or a
         * regular non-monitor job.
         * @param string $type
         * @param timeLimit $timeLimit
         */
        public static function runFromJobManagerCommand($type, $timeLimit, $messageLoggerClassName)
        {
            assert('is_string($type)');
            assert('is_int($timeLimit)');
            assert('is_string($messageLoggerClassName) && (
                    is_subclass_of($messageLoggerClassName, "MessageLogger") ||
                    $messageLoggerClassName == "MessageLogger")');
            set_time_limit($timeLimit);
            $template        = "{message}\n";
            $messageStreamer = new MessageStreamer($template);
            $messageStreamer->setExtraRenderBytes(0);
            $messageStreamer->add(Yii::t('Default', 'Script will run at most for {seconds} seconds.',
                                  array('{seconds}' => $timeLimit)));
            echo "\n";
            $messageStreamer->add(Yii::t('Default', '{dateTimeString} Starting job type: {type}',
                                  array('{type}' => $type,
                                         '{dateTimeString}' => static::getLocalizedDateTimeTimeZoneString())));
            $messageLogger = new $messageLoggerClassName($messageStreamer);
            if ($type == 'Monitor')
            {
                static::runMonitorJob($messageLogger);
            }
            else
            {
                static::runNonMonitorJob($type, $messageLogger);
            }
            $messageStreamer->add(Yii::t('Default', '{dateTimeString} Ending job type: {type}',
                                  array('{type}' => $type,
                                         '{dateTimeString}' => static::getLocalizedDateTimeTimeZoneString())));
        }

        /**
         * Run the monitor job.
         */
        public static function runMonitorJob(MessageLogger $messageLogger)
        {
            try
            {
                $jobInProcess = JobInProcess::getByType('Monitor');
                $messageLogger->addInfoMessage("Existing monitor job detected");
                if (static::isJobInProcessOverThreashold($jobInProcess, 'Monitor'))
                {
                    $messageLogger->addInfoMessage("Existing monitor job is stuck");
                    $message                    = new NotificationMessage();
                    $message->textContent       = MonitorJob::getStuckStringContent();
                    $rules                      = new StuckMonitorJobNotificationRules();
                    NotificationsUtil::submit($message, $rules);
                }
            }
            catch (NotFoundException $e)
            {
                $jobInProcess          = new JobInProcess();
                $jobInProcess->type    = 'Monitor';
                $jobInProcess->save();
                $startDateTime         = $jobInProcess->createdDateTime;
                $job                   = new MonitorJob();
                $job->setMessageLogger($messageLogger);
                $ranSuccessfully       = $job->run();
                $jobInProcess->delete();
                $jobLog                = new JobLog();
                $jobLog->type          = 'Monitor';
                $jobLog->startDateTime = $startDateTime;
                $jobLog->endDateTime   = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
                if ($ranSuccessfully)
                {
                    $messageLogger->addInfoMessage("Monitor Job completed successfully");
                    $jobLog->status        = JobLog::STATUS_COMPLETE_WITHOUT_ERROR;
                }
                else
                {
                    $messageLogger->addInfoMessage("Monitor Job completed with errors");
                    $jobLog->status        = JobLog::STATUS_COMPLETE_WITH_ERROR;
                }
                $jobLog->isProcessed = false;
                $jobLog->save();
            }
        }

        /**
         * Given a 'type' of job, run the job.  This is for non-monitor jobs only.
         * @param string $type
         */
        public static function runNonMonitorJob($type, MessageLogger $messageLogger)
        {
            assert('is_string($type) && $type != "Monitor"');
            try
            {
                $jobInProcess = JobInProcess::getByType($type);
                $messageLogger->addInfoMessage("Existing job detected");
            }
            catch (NotFoundException $e)
            {
                $jobInProcess            = new JobInProcess();
                $jobInProcess->type    = $type;
                $jobInProcess->save();
                $startDateTime         = $jobInProcess->createdDateTime;
                $jobClassName          = $type . 'Job';
                $job                   = new $jobClassName();
                $job->setMessageLogger($messageLogger);
                $ranSuccessfully       = $job->run();
                $errorMessage          = $job->getErrorMessage();
                $jobInProcess->delete();
                $jobLog                = new JobLog();
                $jobLog->type          = $type;
                $jobLog->startDateTime = $startDateTime;
                $jobLog->endDateTime   = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
                if ($ranSuccessfully)
                {
                    $messageLogger->addInfoMessage("Job completed successfully");
                    $jobLog->status        = JobLog::STATUS_COMPLETE_WITHOUT_ERROR;
                }
                else
                {
                    $messageLogger->addInfoMessage("Job completed with errors");
                    $jobLog->status        = JobLog::STATUS_COMPLETE_WITH_ERROR;
                    $jobLog->message       = $errorMessage;
                }
                $jobLog->isProcessed = false;
                $s = $jobLog->save();
            }
        }

        /**
         * Given a model of a jobInProcess and the 'type' of job, determine if the job has been running too
         * long.  Jobs have defined maximum run times that they are allowed to be in process.
         * @param JobInProcess $jobInProcess
         * @param string $type
         * @return true/false - true if the job is over the allowed amount of time to run for.
         */
        public static function isJobInProcessOverThreashold(JobInProcess $jobInProcess, $type)
        {
            assert('is_string($type) && $type != ""');

            $createdTimeStamp  = DateTimeUtil::convertDbFormatDateTimeToTimestamp($jobInProcess->createdDateTime);
            $nowTimeStamp      = time();
            $jobClassName      = $type . 'Job';
            $thresholdSeconds  = $jobClassName::getRunTimeThresholdInSeconds();
            if (($nowTimeStamp - $createdTimeStamp) > $thresholdSeconds)
            {
                return true;
            }
            return false;
        }

        protected static function getLocalizedDateTimeTimeZoneString()
        {
            $content = DateTimeUtil::convertDbFormattedDateTimeToLocaleFormattedDisplay(
                                        DateTimeUtil::convertTimestampToDbFormatDateTime(time()));
            $content .= ' ' . Yii::app()->user->userModel->timeZone;
            return $content;
        }
    }
?>