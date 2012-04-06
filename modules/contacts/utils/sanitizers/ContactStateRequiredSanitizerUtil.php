<?php


    /**
     * Sanitizer for handling contact state. These are states that are the starting state or after.  Manages
     * if the state is required and the value is present.
     */
    class ContactStateRequiredSanitizerUtil extends RequiredSanitizerUtil
    {
        public static function getLinkedMappingRuleType()
        {
            return 'DefaultContactStateId';
        }

        /**
         * Contact state is required.  If the value provided is null then the sanitizer will attempt use a default
         * value if provided.  If this is missing then a InvalidValueToSanitizeException will be thrown.
         * @param string $modelClassName
         * @param string $attributeName
         * @param mixed $value
         * @param array $mappingRuleData
         */
        public static function sanitizeValue($modelClassName, $attributeName, $value, $mappingRuleData)
        {
            assert('is_string($modelClassName)');
            assert('$attributeName == null');
            assert('is_string($value) || $value == null || $value instanceof ContactState');
            $model                  = new $modelClassName(false);
            assert('$mappingRuleData["defaultStateId"] == null || is_string($mappingRuleData["defaultStateId"]) ||
                    is_int($mappingRuleData["defaultStateId"])');
            if ($value == null)
            {
                if ($mappingRuleData['defaultStateId'] != null)
                {
                    try
                    {
                       $state       = ContactState::getById((int)$mappingRuleData['defaultStateId']);
                    }
                    catch (NotFoundException $e)
                    {
                        throw new InvalidValueToSanitizeException(
                        Yii::t('Default', 'The default status specified does not exist.'));
                    }
                    return $state;
                }
                else
                {
                    throw new InvalidValueToSanitizeException(
                    Yii::t('Default', 'The status is required.  Neither a value nor a default was specified.'));
                }
            }
            return $value;
        }
    }
?>