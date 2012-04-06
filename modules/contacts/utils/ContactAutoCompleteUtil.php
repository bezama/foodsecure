<?php


    /**
     * Helper class to convert a contact search into
     * an Jui AutoComplete ready array.
     */
    class ContactAutoCompleteUtil
    {
        /**
         * @return array - Jui AutoComplete ready array
         *  containing id, value, and label elements.
         */
        public static function getByPartialName($partialName, $pageSize, $stateMetadataAdapterClassName = null)
        {
            assert('is_string($partialName)');
            assert('is_int($pageSize)');
            assert('$stateMetadataAdapterClassName == null || is_string($stateMetadataAdapterClassName)');
            $autoCompleteResults  = array();
            $contacts                = ContactSearch::getContactsByPartialFullName($partialName, $pageSize,
                                                            $stateMetadataAdapterClassName = null);
            foreach ($contacts as $contact)
            {
                $autoCompleteResults[] = array(
                    'id'    => $contact->id,
                    'value' => strval($contact),
                    'label' => strval($contact),
                );
            }
            return $autoCompleteResults;
        }
    }
?>