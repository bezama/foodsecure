<?php


    /**
     * Controller Class for managing languages.
     *
     */
    class JobsManagerDefaultController extends fosaModuleController
    {
        public function filters()
        {
            return array(
                array(
                    fosaBaseController::RIGHTS_FILTER_PATH,
                    'moduleClassName' => 'JobsManagerModule',
                    'rightName' => JobsManagerModule::RIGHT_ACCESS_JOBSMANAGER,
               ),
            );
        }

        public function actionIndex()
        {
            $this->actionList();
        }

        public function actionList()
        {
            $this->processListAction();
        }

        protected function processListAction($messageBoxContent = null)
        {
            $view = new JobsManagerTitleBarAndListView(
                            $this->getId(),
                            $this->getModule()->getId(),
                            JobsToJobsCollectionViewUtil::getMonitorJobData(),
                            JobsToJobsCollectionViewUtil::getNonMonitorJobsData(),
                            $messageBoxContent);
            $view = new JobsManagerPageView($this, $view);
            echo $view->render();
        }

        public function actionResetJob($type)
        {
            assert('is_string($type) && $type != ""');
            $jobClassName = $type . 'Job';
            try
            {
                $jobInProcess      = JobInProcess::getByType($type);
                $jobInProcess->delete();
                $messageBoxContent = HtmlNotifyUtil::renderHighlightBoxByMessage(
                                     Yii::t('Default', 'The job {jobName} has been reset.',
                                         array('{jobName}' => $jobClassName::getDisplayName())));
                $this->processListAction($messageBoxContent);
            }
            catch (NotFoundException $e)
            {
                $messageBoxContent = HtmlNotifyUtil::renderHighlightBoxByMessage(
                                 Yii::t('Default', 'The job {jobName} was not found to be stuck and therefore was not reset.',
                                         array('{jobName}' => $jobClassName::getDisplayName())));
                $this->processListAction($messageBoxContent);
            }
        }

        public function actionJobLogsModalList($type)
        {
            assert('is_string($type) && $type != ""');
            $jobClassName = $type . 'Job';
            $searchAttributeData = array();
            $searchAttributeData['clauses'] = array(
                1 => array(
                    'attributeName'        => 'type',
                    'operatorType'         => 'equals',
                    'value'                => $type,
                ),
            );
            $searchAttributeData['structure'] = '1';
            $pageSize     = Yii::app()->pagination->resolveActiveForCurrentUserByType('subListPageSize');
            $dataProvider = new RedBeanModelDataProvider( 'JobLog', 'startDateTime', true,
                                                                $searchAttributeData, array(
                                                                    'pagination' => array(
                                                                        'pageSize' => $pageSize,
                                                                    )
                                                                ));
            Yii::app()->getClientScript()->setToAjaxMode();
            $jobLogsListView = new JobLogsModalListView(
                                    $this->getId(),
                                    $this->getModule()->getId(),
                                    'JobLog',
                                    $dataProvider,
                                    'modal');
            $view = new ModalView($this,
                            $jobLogsListView,
                            'modalContainer',
                            Yii::t('Default', 'Job Log for {jobDisplayName}',
                                   array('{jobDisplayName}' => $jobClassName::getDisplayName())));
            echo $view->render();
        }

        public function actionJobLogDetails($id)
        {
            $jobLog = JobLog::getById(intval($id));
            $view = new JobsManagerPageView($this,
                $this->makeTitleBarAndDetailsView($jobLog));
            echo $view->render();
        }
    }
?>