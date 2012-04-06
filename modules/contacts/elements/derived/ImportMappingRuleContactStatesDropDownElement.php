<?php


    /**
     * Display a drop down of contact states specifically for mapping rules during the import process.
     */
    class ImportMappingRuleContactStatesDropDownElement extends ImportMappingRuleStaticDropDownFormElement
    {
        public function __construct($model, $attribute, $form = null, array $params = array())
        {
            assert('$model instanceof DefaultContactStateIdMappingRuleForm');
            parent::__construct($model, $attribute, $form, $params);
        }

        /**
         * Override to always return true since a blank should always be here.
         * @see DropDownElement::getAddBlank()
         */
        protected function getAddBlank()
        {
            return true;
        }

        protected function getDropDownArray()
        {
            $dropDownArray = $this->model->statesDataAndLabels;
            $language      = Yii::app()->language;
            if (empty($dropDownArray))
            {
                return array();
            }
            return $dropDownArray;
        }
    }
?>
