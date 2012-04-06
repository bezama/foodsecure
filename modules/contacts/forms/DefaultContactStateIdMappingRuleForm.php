<?php


    /**
     * Form for handling default values for the contact state derived attribute type.
     */
    class DefaultContactStateIdMappingRuleForm extends DerivedAttributeMappingRuleForm
    {
        public $defaultStateId;

        protected $statesDataAndLabels;

        protected $statesLabelsByLanguageAndId;

        public function __construct($modelClassName, $modelAttributeName)
        {
            parent::__construct($modelClassName, $modelAttributeName);
            $this->statesDataAndLabels         = static::makeStatesDataAndLabels();
        }

        public function rules()
        {
            if ($this->getScenario() == 'extraColumn')
            {
                $requiredRuleIsApplicable = true;
            }
            else
            {
                $requiredRuleIsApplicable = false;
            }
            $defaultValueApplicableModelAttributeRules = ModelAttributeRulesToDefaultValueMappingRuleUtil::
                                                         getApplicableRulesByModelClassNameAndAttributeName(
                                                         $this->modelClassName,
                                                         'state',
                                                         static::getAttributeName(),
                                                         $requiredRuleIsApplicable);
            return array_merge(parent::rules(), $defaultValueApplicableModelAttributeRules);
        }

        public function getStatesDataAndLabels()
        {
            return $this->statesDataAndLabels;
        }

        public function getStatesLabelsByLanguageAndId()
        {
            return $this->statesLabelsByLanguageAndId;
        }

        public function attributeLabels()
        {
            return array('defaultStateId' => Yii::t('Default', 'Default Value'));
        }

        public static function getAttributeName()
        {
            return 'defaultStateId';
        }

        protected static function makeStatesDataAndLabels()
        {
            return ContactsUtil::getContactStateDataFromStartingStateKeyedByIdAndLabelByLanguage(Yii::app()->language);
        }
    }
?>