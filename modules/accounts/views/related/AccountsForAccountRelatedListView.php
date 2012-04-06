<?php


    class AccountsForAccountRelatedListView extends AccountsRelatedListView
    {
        protected function getRelationAttributeName()
        {
            return 'account';
        }

        public static function getDisplayDescription()
        {
            return Yii::t('Default', 'AccountsModulePluralLabel For AccountsModuleSingularLabel',
                        LabelUtil::getTranslationParamsForAllModules());
        }
    }
?>