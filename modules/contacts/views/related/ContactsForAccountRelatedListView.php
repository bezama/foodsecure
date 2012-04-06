<?php


    class ContactsForAccountRelatedListView extends ContactsRelatedListView
    {
        protected function getRelationAttributeName()
        {
            return 'account';
        }

        public static function getDisplayDescription()
        {
            return Yii::t('Default', 'ContactsModulePluralLabel For AccountsModuleSingularLabel',
                        LabelUtil::getTranslationParamsForAllModules());
        }
    }
?>