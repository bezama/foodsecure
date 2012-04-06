<?php


    /**
     * Cancel conversion link.
     */
    class CancelConvertLinkActionElement extends LinkActionElement
    {
        public function getActionType()
        {
            return 'Details';
        }

        protected function getDefaultLabel()
        {
            return Yii::t('Default', 'Cancel');
        }

        protected function getDefaultRoute()
        {
            if (!empty($this->modelId))
            {
                return Yii::app()->createUrl($this->moduleId . '/' . $this->controllerId . '/details/', array('id' => $this->modelId));
            }
            else
            {
                throw new NotSupportedException();
            }
        }
    }
?>
