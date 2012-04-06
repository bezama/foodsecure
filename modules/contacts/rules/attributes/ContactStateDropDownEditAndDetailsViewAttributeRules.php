<?php


    /**
     * Specific rules for handling the contact state attribute on the EditAndDetailsView.
     */
    class ContactStateDropDownEditAndDetailsViewAttributeRules extends EditAndDetailsViewAttributeRules
    {
        /**
         * Contact state, since required, should not have a blank value for the drop down.
         * @param mixed $value
         */
        public static function getIgnoredSavableMetadataRules()
        {
            return array('AddBlankForDropDown');
        }
    }
?>