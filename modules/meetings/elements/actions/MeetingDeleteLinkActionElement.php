<?php


    class MeetingDeleteLinkActionElement extends DeleteLinkActionElement
    {
        protected function resolveConfirmAlertInHtmlOptions($htmlOptions)
        {
            $htmlOptions['confirm'] = Yii::t('Default',
                                             'Are you sure you want to remove this MeetingsModuleSingularLowerCaseLabel?',
                                             LabelUtil::getTranslationParamsForAllModules());
            return $htmlOptions;
        }
    }
?>