<?php


    class LeadStateDropDownElement extends ContactStateDropDownElement
    {
        protected function getDropDownArray()
        {
            return LeadsUtil::getLeadStateDataFromStartingStateKeyedByIdAndLabelByLanguage(Yii::app()->language);
        }
    }
?>