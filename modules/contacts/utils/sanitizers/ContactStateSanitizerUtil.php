<?php


   /**
     * Sanitizer for handling contact state. These are states that are the starting state or after.
     */
    class ContactStateSanitizerUtil extends SanitizerUtil
    {
        public static function getSqlAttributeValueDataAnalyzerType()
        {
            return 'ContactState';
        }

        public static function getBatchAttributeValueDataAnalyzerType()
        {
            return 'ContactState';
        }

        /**
         * If a state is invalid, then skip the entire row during import.
         */
        public static function shouldNotSaveModelOnSanitizingValueFailure()
        {
            return true;
        }

        /**
         * Given a contact state id, attempt to get and return a contact state object. If the id is invalid, then an
         * InvalidValueToSanitizeException will be thrown.
         * @param string $modelClassName
         * @param string $attributeName
         * @param mixed $value
         * @param array $mappingRuleData
         */
        public static function sanitizeValue($modelClassName, $attributeName, $value, $mappingRuleData)
        {
            assert('is_string($modelClassName)');
            assert('$attributeName == null');
            assert('$mappingRuleData == null');
            if ($value == null)
            {
                return $value;
            }
            try
            {
                if ((int)$value <= 0)
                {
                    $states = ContactState::getByName($value);
                    if (count($states) > 1)
                    {
                        throw new InvalidValueToSanitizeException(Yii::t('Default', 'The status specified is not unique and is invalid.'));
                    }
                    elseif (count($states) == 0)
                    {
                        throw new NotFoundException();
                    }
                    $state = $states[0];
                }
                else
                {
                    $state = ContactState::getById($value);
                }
                $startingState = ContactsUtil::getStartingState();
                if (!static::resolvesValidStateByOrder($state->order, $startingState->order))
                {
                    throw new InvalidValueToSanitizeException(Yii::t('Default', 'The status specified is invalid.'));
                }
                return $state;
            }
            catch (NotFoundException $e)
            {
                throw new InvalidValueToSanitizeException(Yii::t('Default', 'The status specified does not exist.'));
            }
        }

        protected static function resolvesValidStateByOrder($stateOrder, $startingOrder)
        {
            if ($stateOrder >= $startingOrder)
            {
                return true;
            }
            return false;
        }
    }
?>