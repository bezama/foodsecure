<?php


    class DashboardTitleBarAndEditView extends GridView
    {
        public function __construct($controllerId, $moduleId, Dashboard $model)
        {
            parent::__construct(2, 1);
            $this->setView(new TitleBarView ($model::getModelLabelByTypeAndLanguage('Plural'), $model->name), 0, 0);
            $this->setView(new DashboardEditView($controllerId, $moduleId, $model), 1, 0);
        }
    }
?>