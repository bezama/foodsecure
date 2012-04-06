<?php


    class EditDashboardLinkActionElement extends LinkActionElement
    {
        public function getActionType()
        {
            return 'Edit';
        }

        protected function getDefaultLabel()
        {
            return Yii::t('Default', 'Edit Dashboard');
        }

        protected function getDefaultRoute()
        {
            return Yii::app()->createUrl($this->moduleId . '/' . $this->controllerId . '/editDashboard/', array('id' => $this->modelId));
        }
    }
?>