<?php


    class HomeTitleBarAndDashboardView extends GridView
    {
        public function __construct($controllerId, $moduleId, $uniqueLayoutId, $model, $params)
        {
            parent::__construct(2, 1);
            $this->setView(new TitleBarView (strval($model)), 0, 0);
            $this->setView(new HomeDashboardView(
                $controllerId,
                $moduleId,
                $uniqueLayoutId,
                $model,
                $params),
            1, 0);
        }
    }
?>