<?php


    class ConvertLinkActionElement extends LinkActionElement
    {
        public function getActionType()
        {
            return 'ConvertLead';
        }

        protected function getDefaultLabel()
        {
            return Yii::t('Default', 'Convert');
        }

        protected function getDefaultRoute()
        {
            return Yii::app()->createUrl($this->moduleId . '/' . $this->controllerId . '/convert/', array('id' => $this->modelId));
        }
    }
?>