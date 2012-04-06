<?php


    class AccountAttributeForm extends AttributeForm
    {
        public static function getAttributeTypeDisplayName()
        {
            return Yii::t('Default', 'Account');
        }

        public static function getAttributeTypeDisplayDescription()
        {
            return Yii::t('Default', 'An account field');
        }

        public function getAttributeTypeName()
        {
            return 'Account';
        }
    }
?>