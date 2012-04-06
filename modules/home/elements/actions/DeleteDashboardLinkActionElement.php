<?php


    class DeleteDashboardLinkActionElement extends LinkActionElement
    {
        public function getActionType()
        {
            return 'Delete';
        }

        protected function getDefaultLabel()
        {
            return Yii::t('Default', 'Delete Dashboard');
        }

        protected function getDefaultRoute()
        {
            return Yii::app()->createUrl($this->moduleId . '/' . $this->controllerId . '/DeleteDashboard/',
                    array('dashboardId' => $this->modelId)
                );
        }
    }
?>