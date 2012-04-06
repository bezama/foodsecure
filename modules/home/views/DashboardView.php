<?php


    /**
     * The base View for a dashboard view
     */
    abstract class DashboardView extends PortletFrameView
    {
        protected $model;
        protected $isDefaultDashboard;

        public function __construct($controllerId, $moduleId, $uniqueLayoutId, $model, $params)
        {
            $this->controllerId        = $controllerId;
            $this->moduleId            = $moduleId;
            $this->uniqueLayoutId      = $uniqueLayoutId;
            $this->model               = $model;
            $this->modelId             = $model->id;
            $this->layoutType          = $model->layoutType;
            $this->isDefaultDashboard  = $model->isDefault;
            $this->params              = $params;
        }

        /**
         * Override to allow for making a default set of portlets
         * via metadata optional.
         *
         */
        protected function getPortlets($uniqueLayoutId, $metadata)
        {
            assert('is_string($uniqueLayoutId)');
            assert('is_array($metadata)');
            $portlets = Portlet::getByLayoutIdAndUserSortedByColumnIdAndPosition($uniqueLayoutId, Yii::app()->user->userModel->id, $this->params);
            if (empty($portlets) && $this->isDefaultDashboard)
            {
                $portlets = Portlet::makePortletsUsingMetadataSortedByColumnIdAndPosition($uniqueLayoutId, $metadata, Yii::app()->user->userModel, $this->params);
                Portlet::savePortlets($portlets);
            }
            return PortletsSecurityUtil::resolvePortletsForCurrentUser($portlets);
        }

        protected function renderContent()
        {
            $content  = '<div class="view-toolbar">';
            $content .= $this->renderActionElementBar(false);
            $content .= '</div>';
            $this->portlets = $this->getPortlets($this->uniqueLayoutId, self::getMetadata());
            $content .= $this->renderPortlets($this->uniqueLayoutId);
            return $content;
        }

        /**
         * Render a toolbar above the form layout. This includes
         * a link to edit the dashboard as well as a link to add
         * portlets to the dashboard
         * @return A string containing the element's content.
          */
        protected function renderActionElementBar($renderedInForm)
        {
            $content = parent::renderActionElementBar($renderedInForm);

            $deleteDashboardLinkActionElement  = new DeleteDashboardLinkActionElement(
                $this->controllerId,
                $this->moduleId,
                $this->modelId,
                array('htmlOptions' => array('confirm' => Yii::t('Default', 'Are you sure want to delete this dashboard?')))
            );
            if (!ActionSecurityUtil::canCurrentUserPerformAction($deleteDashboardLinkActionElement->getActionType(), $this->model))
            {
                return $content;
            }
            if (!$this->isDefaultDashboard)
            {
                $content .= '&#160;|&#160;' . $deleteDashboardLinkActionElement->render();
            }
            return $content;
        }
    }
?>
