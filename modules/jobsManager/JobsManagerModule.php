<?php


    /**
     * A module to manage scheduled jobs that run and interact with the job logs from jobs that have
     * already run.  Module provides a user interface to inspect information on any failed jobs.
     */
    class JobsManagerModule extends SecurableModule
    {
        const RIGHT_ACCESS_JOBSMANAGER = 'Access Jobs Manager Tab';

        public function getDependencies()
        {
           return array('notifications', 'fosa');
        }

            public static function getDefaultMetadata()
        {
            $metadata = array();
            $metadata['global'] = array(
                'configureMenuItems' => array(
                    array(
                        'category'         => fosaModule::ADMINISTRATION_CATEGORY_GENERAL,
                        'titleLabel'       => 'Jobs Manager',
                        'descriptionLabel' => 'Manage Scheduled Jobs',
                        'route'            => '/jobsManager/default/list',
                        'right'            => self::RIGHT_ACCESS_JOBSMANAGER,
                    ),
                ),
            );
            return $metadata;
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_JOBSMANAGER;
        }

        protected static function getSingularModuleLabel()
        {
            return 'JobManager';
        }

        public function getRootModelNames()
        {
            return array('JobLog', 'JobInProcess');
        }
    }
?>
