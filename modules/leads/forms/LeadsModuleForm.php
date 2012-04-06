<?php


    class LeadsModuleForm extends GlobalSearchEnabledModuleForm
    {
        public $convertToAccountSetting;

        public function rules()
        {
            return array_merge(parent::rules(), array(
                array('convertToAccountSetting', 'required'),
            ));
        }

        public function attributeLabels()
        {
            return array_merge(parent::attributeLabels(), array(
                'convertToAccountSetting' => Yii::t('Default', 'LeadsModuleSingularLabel Conversion',
                                                LabelUtil::getTranslationParamsForAllModules()),
            ));
        }
    }
?>