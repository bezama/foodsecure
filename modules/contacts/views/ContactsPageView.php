<?php


    class ContactsPageView extends fosaPageView
    {
        public function __construct(CController $controller, View $view)
        {
            parent::__construct(new fosaDefaultView($controller, $view));
        }

        protected function getSubtitle()
        {
            return Yii::t('Default', 'ContactsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules());
        }
    }
?>
