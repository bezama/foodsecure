<?php


    /**
     * Helper class with functions
     * to assist in working with Contacts module
     * information
     */
    class ContactsUtil
    {
        /**
         * Given an array of states, determine what the startingState
         * order number is.
         * @return int order
         */
        public static function getStartingStateOrder(array $states)
        {
            $metadata = ContactsModule::getMetadata();
            $startingState = $metadata['global']['startingStateId'];
            $startingStateOrder = 0;
            foreach ($states as $state)
            {
                if ($state->id == $startingState)
                {
                    $startingStateOrder = $state->order;
                    break;
                }
            }
            return $startingStateOrder;
        }

        /**
         * @return ContactState object
         */
        public static function getStartingState()
        {
            $metadata = ContactsModule::getMetadata();
            return ContactState::getById($metadata['global']['startingStateId']);
        }

        /**
         * @return integer Id
         */
        public static function getStartingStateId()
        {
            $metadata = ContactsModule::getMetadata();
            return $metadata['global']['startingStateId'];
        }

        /**
         * Get an array of order/name pairings of the existing contact states ordered by order.
         * @return array
         */
        public static function getContactStateDataKeyedByOrder()
        {
            $contactStatesData = array();
            $states = ContactState::getAll('order');
            foreach ($states as $state)
            {
                $contactStatesData[$state->order] = $state->name;
            }
            return $contactStatesData;
        }

        /**
         * Get an array of order/ label translation array pairings of the existing contact states ordered by order.
         * @return array
         */
        public static function getContactStateLabelsKeyedByLanguageAndOrder()
        {
            $contactStatesLabels = null;
            $states = ContactState::getAll('order');
            foreach ($states as $state)
            {
                if ($state->serializedLabels !== null)
                {
                    $labelsByLanguage = unserialize($state->serializedLabels);
                    foreach ($labelsByLanguage as $language => $label)
                    {
                        $contactStatesLabels[$language][$state->order] = $label;
                    }
                }
            }
            return $contactStatesLabels;
        }

        /**
         * Get an array of order/name pairings of the existing contact states ordered by order.
         * @return array
         */
        public static function getContactStateDataKeyedById()
        {
            $contactStatesData = array();
            $states = ContactState::getAll('order');
            foreach ($states as $state)
            {
                $contactStatesData[$state->id] = $state->name;
            }
            return $contactStatesData;
        }

        /**
         * Get an array of only the states from the starting state onwards, id/name pairings of the
         * existing contact states ordered by order.
         * @return array
         */
        public static function getContactStateDataFromStartingStateOnAndKeyedById()
        {
            $contactStatesData = array();
            $states            = ContactState::getAll('order');
            $startingState     = self::getStartingStateId();
            $includeState      = false;
            foreach ($states as $state)
            {
                if ($startingState == $state->id || $includeState)
                {
                    if ($startingState == $state->id)
                    {
                        $includeState = true;
                    }
                    $contactStatesData[$state->id] = $state->name;
                }
            }
            return $contactStatesData;
        }

        /**
         * Get an array of only the states from the starting state onwards, id/translated label pairings of the
         * existing contact states ordered by order.
         * @return array
         */
        public static function getContactStateDataFromStartingStateKeyedByIdAndLabelByLanguage($language)
        {
            assert('is_string($language)');
            $contactStatesData = array();
            $states            = ContactState::getAll('order');
            $startingState     = self::getStartingStateId();
            $includeState      = false;
            foreach ($states as $state)
            {
                if ($startingState == $state->id || $includeState)
                {
                    if ($startingState == $state->id)
                    {
                        $includeState = true;
                    }
                    $contactStatesData[$state->id] = static::resolveStateLabelByLanguage($state, $language);
                }
            }
            return $contactStatesData;
        }

        public static function setStartingStateById($startingStateId)
        {
            assert('is_int($startingStateId)');
            $metadata = ContactsModule::getMetadata();
            $metadata['global']['startingStateId'] = $startingStateId;
            ContactsModule::setMetadata($metadata);
        }

        public static function setStartingStateByOrder($startingStateOrder)
        {
            $states = ContactState::getAll('order');
            foreach ($states as $order => $state)
            {
                if ($startingStateOrder == $state->order)
                {
                    self::setStartingStateById($state->id);
                    return;
                }
            }
            throw new NotSupportedException();
        }

        /**
         * Given two module class names and a user, resolve based on the user's access what if any adapter should
         * be utilized.  If the user has access to both modules, then return null. If the user has access to none
         * of the modules, then return false. Otherwise return a string with the name of the appropriate adapter
         * to use.
         * @param string $moduleClassNameFirstStates
         * @param string $moduleClassNameLaterStates
         * @param object $user User model
         */
        public static function resolveContactStateAdapterByModulesUserHasAccessTo(  $moduleClassNameFirstStates,
                                                                                    $moduleClassNameLaterStates,
                                                                                    $user)
        {
            assert('is_string($moduleClassNameFirstStates)');
            assert('is_string($moduleClassNameLaterStates)');
            assert('$user instanceof User && $user->id > 0');
            $canAccessFirstStatesModule  = RightsUtil::canUserAccessModule($moduleClassNameFirstStates, $user);
            $canAccessLaterStatesModule = RightsUtil::canUserAccessModule($moduleClassNameLaterStates, $user);
            if ($canAccessFirstStatesModule && $canAccessLaterStatesModule)
            {
                return null;
            }
            elseif (!$canAccessFirstStatesModule && $canAccessLaterStatesModule)
            {
                $prefix = substr($moduleClassNameLaterStates, 0, strlen($moduleClassNameLaterStates) - strlen('Module'));
                return $prefix . 'StateMetadataAdapter';
            }
            elseif ($canAccessFirstStatesModule && !$canAccessLaterStatesModule)
            {
                $prefix = substr($moduleClassNameFirstStates, 0, strlen($moduleClassNameFirstStates) - strlen('Module'));
                return $prefix . 'StateMetadataAdapter';
            }
            else
            {
                return false;
            }
        }

        /**
         * Given a CustomFieldData object, return an array of data and translated labels indexed by the data name.
         * @param CustomFieldData $customFieldData
         * $param string $language
         */
        public static function resolveStateLabelByLanguage(ContactState $state, $language)
        {
            assert('$state->id > 0');
            assert('is_string($language)');
            if ($state->serializedLabels !== null)
            {
                $unserializedLabels = unserialize($state->serializedLabels);
                if (isset($unserializedLabels[$language]))
                {
                    return $unserializedLabels[$language];
                }
            }
            return Yii::t('Default', $state->name, array(), null, $language);
        }
    }
?>