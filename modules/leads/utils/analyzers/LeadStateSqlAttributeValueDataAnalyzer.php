<?php


    /**
     * Data analyzer for contact state values that are before the starting state. This means it is a lead value.
     */
    class LeadStateSqlAttributeValueDataAnalyzer extends ContactStateSqlAttributeValueDataAnalyzer
    {
        protected function resolveStates()
        {
            return LeadsUtil::getLeadStateDataFromStartingStateOnAndKeyedById();
        }
    }
?>