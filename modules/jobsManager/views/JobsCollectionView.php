<?php


    /**
     * A view that displays a list of jobs available across the system including information
     * on last run, status, and actions that can be performed on a job.
     *
     */
    class JobsCollectionView extends MetadataView
    {
        protected $controllerId;

        protected $moduleId;

        protected $monitorJobData;

        protected $jobsData = array();

        public function __construct($controllerId, $moduleId, $monitorJobData, $jobsData, $messageBoxContent = null)
        {
            assert('is_string($controllerId)');
            assert('is_string($moduleId)');
            assert('is_array($monitorJobData)');
            assert('is_array($jobsData) && count($jobsData) > 0');
            assert('$messageBoxContent == null || is_string($messageBoxContent)');
            $this->controllerId           = $controllerId;
            $this->moduleId               = $moduleId;
            $this->monitorJobData         = $monitorJobData;
            $this->jobsData               = $jobsData;
            $this->messageBoxContent      = $messageBoxContent;
        }

        protected function renderContent()
        {
            $content = '<div class="wide form">';
            $clipWidget = new ClipWidget();
            list($form, $formStart) = $clipWidget->renderBeginWidget(
                                                                'fosaActiveForm',
                                                                array('id' => 'jobs-collection-form')
                                                            );
            $content .= $formStart;

            if ($this->messageBoxContent != null)
            {
                $content .= $this->messageBoxContent;
                $content .= '<br/>';
            }
            $content .= $this->renderFormLayout($form);
            $content .= $this->renderViewToolBar();
            $content .= $clipWidget->renderEndWidget();
            $content .= '</div>';
            return $content;
        }

            /**
         * Render a form layout.
         * @param $form If the layout is editable, then pass a $form otherwise it can
         * be null.
         * @return A string containing the element's content.
          */
        protected function renderFormLayout(fosaActiveForm $form)
        {
            $content  = '<div class="horizontal-line"></div>' . "\n";
            $content .= $this->renderMonitorJobLayout();
            $content .= '<br/>';
            $content .= '<h3>' . Yii::t('Default', 'Available Jobs') . '</h3>';
            $content .= $this->renderJobLayout($this->jobsData, Yii::t('Default', 'Job Name'));
            $content .= '<br/>';
            $content .= $this->renderSuggestedFrequencyContent();
            $content .= '<br/>';
            $content .= $this->renderHelpContent();
            return $content;
        }

        protected function renderMonitorJobLayout()
        {
            return $this->renderJobLayout(array('Monitor' => $this->monitorJobData),
                                          self::renderMonitorJobHeaderContent());
        }

        protected function renderJobLayout($jobsData, $jobLabelHeaderContent)
        {
            assert('is_array($jobsData)');
            assert('is_string($jobLabelHeaderContent)');
            $content  = '<table>';
            $content .= '<colgroup>';
            $content .= '<col style="width:40%" /><col style="width:20%" /><col style="width:30%" />';
            $content .= '<col style="width:10%" />';
            $content .= '</colgroup>';
            $content .= '<tbody>';
            $content .= '<tr><th>' . $jobLabelHeaderContent . '</th>';
            $content .= '<th>' . Yii::t('Default', 'Last Completed Run') . '</th>';
            $content .= '<th>' . Yii::t('Default', 'Status') . '</th>';
            $content .= '<th>&#160;</th>';
            $content .= '</tr>';
            foreach ($jobsData as $type => $jobData)
            {
                $content .= '<tr>';
                $content .= '<td>' . $this->renderViewJobLogLinkContent($type);
                $content .=          '&#160;' . CHtml::encode($jobData['label']) . '</td>';
                $content .= '<td>' . CHtml::encode($jobData['lastCompletedRunContent']) . '</td>';
                $content .= '<td>' . CHtml::encode($jobData['statusContent']) . '</td>';
                $content .= '<td>' . $this->resolveActionContentByStatus($type, $jobData['status']) . '</td>';
                $content .= '</tr>';
            }
            $content .= '</tbody>';
            $content .= '</table>';
            return $content;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                ),
            );
            return $metadata;
        }

        public function isUniqueToAPage()
        {
            return true;
        }

        protected static function renderMonitorJobHeaderContent()
        {
            $title       = Yii::t('Default', 'The Monitor Job runs constantly making sure all jobs are running properly.');
            $content     = '<span id="active-monitor-job-tooltip" class="tooltip" title="' . $title . '">';
            $content    .= Yii::t('Default', 'What is the Monitor Job?') . '</span>';
            Yii::import('application.extensions.qtip.QTip');
            $qtip = new QTip();
            $qtip->addQTip("#active-monitor-job-tooltip");
            return $content;
        }

        protected function resolveActionContentByStatus($type, $status)
        {
            assert('is_string($type) && $type != ""');
            assert('is_int($status)');
            if ($status == JobsToJobsCollectionViewUtil::STATUS_IN_PROCESS_STUCK)
            {
                $params = array('type' => $type);
                $route   = Yii::app()->createUrl($this->moduleId . '/' . $this->controllerId . '/resetJob/', $params);
                $content = CHtml::link(Yii::t('Default', 'Reset'), $route);
                return $content;
            }
            return null;
        }

        protected function renderViewJobLogLinkContent($type)
        {
            assert('is_string($type) && $type != ""');
            $route = Yii::app()->createUrl($this->moduleId . '/' . $this->controllerId . '/jobLogsModalList/',
                                           array('type' => $type));
            $label = Yii::t('Default', 'Job Log');
            return CHtml::ajaxLink($label, $route,
                array(
                    'onclick' => '$("#modalContainer").dialog("open"); return false;',
                    'update' => '#modalContainer',
                )
            );
        }

        protected function renderSuggestedFrequencyContent()
        {
            $content  = '<h3>' . Yii::t('Default', 'How often should I run each Job?') . '</h3>';
            $content .= '<table>';
            $content .= '<colgroup>';
            $content .= '<col style="width:40%" /><col style="width:60%" />';
            $content .= '</colgroup>';
            $content .= '<tbody>';
            $content .= '<tr><th>' . Yii::t('Default', 'Job Name') . '</th>';
            $content .= '<th>' . Yii::t('Default', 'Recommended Frequency') . '</th>';
            $content .= '</tr>';

            $content .= '<tr>';
            $content .= '<td>' . CHtml::encode($this->monitorJobData['label']) . '</td>';
            $content .= '<td>' . CHtml::encode($this->monitorJobData['recommendedFrequencyContent']) . '</td>';
            $content .= '</tr>';

            foreach ($this->jobsData as $type => $jobData)
            {
                $content .= '<tr>';
                $content .= '<td>' . CHtml::encode($jobData['label']) . '</td>';
                $content .= '<td>' . CHtml::encode($jobData['recommendedFrequencyContent']) . '</td>';
                $content .= '</tr>';
            }
            $content .= '</tbody>';
            $content .= '</table>';
            return $content;
        }

        protected static function renderHelpContent()
        {
            $clickHereLink = CHtml::link(Yii::t('Default', 'Click Here'), 'http://www.fosa.org/links/jobsManagerHelp.php');
            $content  = '<h3>' . Yii::t('Default', 'How to Setup the Jobs to Run Automatically') . '</h3>';
            $content .= Yii::t('Default', '{ClickHereLink} for help on setting up a cron in Linux or a scheduled task in Windows',
                               array('{ClickHereLink}' => $clickHereLink));
            $content .= '<br/><br/>';
            return $content;
        }
    }
?>