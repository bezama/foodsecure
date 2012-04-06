<?php


    class LeadsModuleAttributesListView extends GridView
    {
        public function __construct(
            $controllerId,
            $moduleId,
            $breadcrumbLinks
        )
        {
            parent::__construct(3, 1);
            $moduleDisplayName = LeadsModule::getModuleLabelByTypeAndLanguage('Plural');
            $this->setView(new TitleBarView(Yii::t('Default', $moduleDisplayName), Yii::t('Default', 'Standard Fields')), 0, 0);
            $this->setView(new DesignerBreadCrumbView($controllerId, $moduleId, $breadcrumbLinks), 1, 0);
            $this->setView(new AttributesRedirectToContactsView($controllerId, $moduleId), 2, 0);
        }

        public function isUniqueToAPage()
        {
            return true;
        }
    }
?>