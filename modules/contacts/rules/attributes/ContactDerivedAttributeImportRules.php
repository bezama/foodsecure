<?php


    /**
     * Import rules for when a contact is not a specific attribute on a model, but most likely a casted down
     * model of an existing attribute.  An example would be in the activity model and the activityItems relation.
     * This relation does not point to a casted down model, but ultimately refers to it.
     * @see DefaultModelNameIdDerivedAttributeMappingRuleForm
     */
    class ContactDerivedAttributeImportRules extends ModelDerivedAttributeImportRules
    {
        public static function getSanitizerUtilTypesInProcessingOrder()
        {
            return array('ContactDerivedIdValueType');
        }
    }
?>