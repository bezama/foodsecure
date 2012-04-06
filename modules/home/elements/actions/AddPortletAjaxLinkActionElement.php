<?php


    class AddPortletAjaxLinkActionElement extends AjaxLinkActionElement
    {
        public function getActionType()
        {
            return 'AddPortlet';
        }

        protected function getDefaultLabel()
        {
            return Yii::t('Default', 'Add Portlet');
        }

        protected function getDefaultRoute()
        {
            return Yii::app()->createUrl($this->moduleId . '/defaultPortlet/AddList/',
                    array(
                    'uniqueLayoutId' => $this->getUniqueLayoutId(),
                    'dashboardId' => $this->modelId,
                    )
            );
        }

        protected function getUniqueLayoutId()
        {
            if (isset($this->params['uniqueLayoutId']))
            {
                return $this->params['uniqueLayoutId'];
            }
        }
    }
?>