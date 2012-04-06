<?php


    /**
     * Adapter class to adapt active states for the leads module
     */
    class LeadsStateMetadataAdapter extends ContactsStateMetadataAdapter
    {
        protected function shouldIncludeState($stateOrder, $startingStateOrder)
        {
            return $stateOrder < $startingStateOrder;
        }
    }
?>