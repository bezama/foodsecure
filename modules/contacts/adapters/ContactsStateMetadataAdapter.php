<?php


    /**
     * Adapter class to adapt active states for the contacts module
     */
    class ContactsStateMetadataAdapter extends StateMetadataAdapter
    {
        protected function getStateIds()
        {
            $states = ContactState::getAll('order');
            $startingStateOrder = ContactsUtil::getStartingStateOrder($states);
            $stateIds = array();
            foreach ($states as $state)
            {
                if ($this->shouldIncludeState($state->order, $startingStateOrder))
                {
                    $stateIds[] = $state->id;
                }
            }
            return $stateIds;
        }

        protected function shouldIncludeState($stateOrder, $startingStateOrder)
        {
            return $stateOrder >= $startingStateOrder;
        }

        public static function getStateModelClassName()
        {
            return 'ContactState';
        }
    }
?>