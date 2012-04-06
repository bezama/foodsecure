<?php


    /**
     * Helper class with functions
     * to assist in working with Leads module
     * information
     */
    class LeadsUtil
    {
        /**
         * Given a contact and an account, use the mapping in the
         * Leads Module to copy attributes from contact to Account
         * order number is.
         * @param $contact Contact model
         * @param $account Account model
         * @return Account, with mapped attributes from Contact
         */
        public static function AttributesToAccount(Contact $contact, Account $account)
        {
            assert('!empty($contact->id)');
            $metadata = LeadsModule::getMetadata();
            $map = $metadata['global']['convertToAccountAttributesMapping'];
            foreach ($map as $contactAttributeName => $accountAttributeName)
            {
                $account->$accountAttributeName = $contact->$contactAttributeName;
            }
            return $account;
        }

        /**
         * Given a post data array, map the lead to account attributes
         * but only if the post data does not contain a set attribute.
         * This method is used when a posted form has an empty value on
         * an input field.  We do not want to set the mapped field since
         * the use of setAttributes will pick up the correct information
         * from the posted data.  This will allow form validation to work
         * properly in the case where a mapped field is cleared to blank
         * in the input field and submitted. Such an event should trigger
         * a form validation error.
         * @see LeadsUtil::AttributesToAccount
         * @param $contact Contact model
         * @param $account Account model
         * @param $postData array of posted form data
         * @return Account, with mapped attributes from Contact
         */
        public static function AttributesToAccountWithNoPostData(Contact $contact, Account $account, array $postData)
        {
            assert('is_array($postData)');
            assert('!empty($contact->id)');
            $metadata = LeadsModule::getMetadata();
            $map = $metadata['global']['convertToAccountAttributesMapping'];
            foreach ($map as $contactAttributeName => $accountAttributeName)
            {
                if (!isset($postData[$accountAttributeName]))
                {
                    $account->$accountAttributeName = $contact->$contactAttributeName;
                }
            }
            return $account;
        }

        /**
         * If no states exist, throws MissingContactsStartingStateException
         * @return ContactState object
         */
        public static function getStartingState()
        {
            $states = ContactState::getAll('order');
            if (count($states) == 0)
            {
                throw new MissingContactsStartingStateException();
            }
            return $states[0];
        }

        /**
         * Get an array of only the states from the starting state onwards, order/name pairings of the
         * existing lead states ordered by order.
         * @return array
         */
        public static function getLeadStateDataFromStartingStateOnAndKeyedById()
        {
            $leadStatesData = array();
            $states            = ContactState::getAll('order');
            $startingState     = ContactsUtil::getStartingStateId();
            foreach ($states as $state)
            {
                if ($startingState == $state->id)
                {
                    break;
                }
                $leadStatesData[$state->id] = $state->name;
            }
            return $leadStatesData;
        }

        /**
         * Get an array of only the states from the starting state onwards, order/translated label pairings of the
         * existing lead states ordered by order.
         * @return array
         */
        public static function getLeadStateDataFromStartingStateKeyedByIdAndLabelByLanguage($language)
        {
            assert('is_string($language)');
            $leadStatesData = array();
            $states            = ContactState::getAll('order');
            $startingState     = ContactsUtil::getStartingStateId();
            foreach ($states as $state)
            {
                if ($startingState == $state->id)
                {
                    break;
                }
                $leadStatesData[$state->id] = ContactsUtil::resolveStateLabelByLanguage($state, $language);
            }
            return $leadStatesData;
        }

        public static function isStateALead(ContactState $state)
        {
            assert('$state->id > 0');
            $leadStatesData = self::getLeadStateDataFromStartingStateOnAndKeyedById();
            if (isset($leadStatesData[$state->id]))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
?>