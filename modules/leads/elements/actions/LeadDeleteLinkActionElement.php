<?php


    class LeadDeleteLinkActionElement extends DeleteLinkActionElement
    {
        protected function resolveConfirmAlertInHtmlOptions($htmlOptions)
        {
            $htmlOptions['confirm'] = Yii::t('Default',
                                             'Are you sure you want to remove this LeadsModuleSingularLowerCaseLabel?',
                                             LabelUtil::getTranslationParamsForAllModules());
            return $htmlOptions;
        }
    }
?>