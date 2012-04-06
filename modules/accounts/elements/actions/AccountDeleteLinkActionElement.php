<?php


    class AccountDeleteLinkActionElement extends DeleteLinkActionElement
    {
        protected function resolveConfirmAlertInHtmlOptions($htmlOptions)
        {
            $htmlOptions['confirm'] = Yii::t('Default',
                                             'Are you sure you want to remove this AccountsModuleSingularLowerCaseLabel?',
                                             LabelUtil::getTranslationParamsForAllModules());
            return $htmlOptions;
        }
    }
?>