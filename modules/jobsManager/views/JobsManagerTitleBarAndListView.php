<?php


    class JobsManagerTitleBarAndListView extends GridView
    {
        public function __construct(
            $controllerId,
            $moduleId,
            $monitorJobData,
            $jobsData,
            $messageBoxContent = null)
        {
            parent::__construct(2, 1);
            $this->setView(new TitleBarView (Yii::t('Default', 'Jobs Manager: Home')), 0, 0);
            $this->setView(new JobsCollectionView($controllerId, $moduleId, $monitorJobData, $jobsData, $messageBoxContent), 1, 0);
        }
    }
?>