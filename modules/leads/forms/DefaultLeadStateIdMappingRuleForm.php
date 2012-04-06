<?php


    /**
     * Form for handling default values for the contact state derived attribute type.
     */
    class DefaultLeadStateIdMappingRuleForm extends DefaultContactStateIdMappingRuleForm
    {
        protected static function makeStatesDataAndLabels()
        {
            return LeadsUtil::getLeadStateDataFromStartingStateKeyedByIdAndLabelByLanguage(Yii::app()->language);
        }
    }
?>