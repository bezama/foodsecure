<?php


    class HomeDashboardPortletSelectionView extends View
    {
        protected $controllerId;
        protected $moduleId;
        protected $dashboardId;
        protected $uniqueLayoutId;

        public function __construct($controllerId, $moduleId, $dashboardId, $uniqueLayoutId)
        {
            $this->controllerId   = $controllerId;
            $this->moduleId       = $moduleId;
            $this->dashboardId    = $dashboardId;
            $this->uniqueLayoutId = $uniqueLayoutId;
        }

        protected function renderContent()
        {
            $content = null;
            $modules = Module::getModuleObjects();
            foreach ($modules as $module)
            {
                if ($module->isEnabled())
                {
                    $p = $module->getParentModule();
                    $viewClassNames = $module::getViewClassNames();
                    foreach ($viewClassNames as $className)
                    {
                        $viewReflectionClass = new ReflectionClass($className);
                        if (!$viewReflectionClass->isAbstract())
                        {
                            $portletRules = PortletRulesFactory::createPortletRulesByView($className);
                            if ($portletRules != null && $portletRules->allowOnDashboard())
                            {
                                $metadata = $className::getMetadata();
                                $url = Yii::app()->createUrl($this->moduleId . '/defaultPortlet/add', array(
                                    'uniqueLayoutId' => $this->uniqueLayoutId,
                                    'dashboardId'    => $this->dashboardId,
                                    'portletType'    => $portletRules->getType(),
                                    )
                                );
                                $onClick = 'window.location.href = "' . $url . '"';
                                $content .= CHtml::button(Yii::t('Default', 'Select'), array('onClick' => $onClick));
                                $title    = $metadata['perUser']['title'];
                                MetadataUtil::resolveEvaluateSubString($title);
                                $content .= '&#160;' . $title;
                                $content .= '<br/>';
                            }
                        }
                    }
                }
            }
            return $content;
        }
    }
?>
