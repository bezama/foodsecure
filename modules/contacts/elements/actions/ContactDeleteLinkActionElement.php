<?php


    class ContactDeleteLinkActionElement extends DeleteLinkActionElement
    {
        protected function resolveConfirmAlertInHtmlOptions($htmlOptions)
        {
            $htmlOptions['confirm'] = Yii::t('Default',
                                             'Are you sure you want to remove this ContactsModuleSingularLowerCaseLabel?',
                                             LabelUtil::getTranslationParamsForAllModules());
            return $htmlOptions;
        }
    }
?>