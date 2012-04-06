<?php


    /**
     * Form used for selecting an account
     */
    class AccountSelectForm extends CFormModel
    {
        public $accountId;
        public $accountName;

        /**
         * Override to handle use case of $name == 'id'.
         * As this form does not have an 'id', it will return null;
         * @see ModelElement.  This form is used by ModelElement for example
         * and ModelElement expects the model to have an 'id' value.
         */
        public function __get($name)
        {
            if ($name == 'id')
            {
                return null;
            }
            return parent::__get($name);
        }

        public function rules()
        {
            return array(
                array('accountId',   'type',    'type' => 'integer'),
                array('accountId',   'required'),
                array('accountName', 'required'),
            );
        }

        public function attributeLabels()
        {
            return array(
                'accountId'          => Yii::t('Default', 'AccountsModuleSingularLabel Id',
                                            LabelUtil::getTranslationParamsForAllModules()),
                'accountName'        => Yii::t('Default', 'AccountsModuleSingularLabel Name',
                                            LabelUtil::getTranslationParamsForAllModules()),
            );
        }
    }
?>