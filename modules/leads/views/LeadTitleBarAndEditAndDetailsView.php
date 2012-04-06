<?php


    class LeadTitleBarAndEditAndDetailsView extends GridView
    {
        public function __construct($controllerId, $moduleId, RedBeanModel $model, $moduleName, $renderType)
        {
            assert('$renderType == "Edit" || $renderType == "Details"');
            parent::__construct(2, 1);
            $moduleClassName = $moduleName . 'Module';
            $menuItems = MenuUtil::getAccessibleShortcutsMenuByCurrentUser($moduleClassName);
            $shortcutsMenu = new DropDownShortcutsMenuView(
                $controllerId,
                $moduleId,
                $menuItems
            );
            $this->setView(new TitleBarView (Yii::t('Default', 'Leads'), $model, 1, $shortcutsMenu->render()), 0, 0);
            $editViewClassName = 'LeadEditAndDetailsView';
            $this->setView(new $editViewClassName($renderType, $controllerId, $moduleId, $model), 1, 0);
        }
    }
?>