<?php


    /**
     * Sanitizer for contact states that are before the starting state. Used by the leads module.
     */
    class LeadStateSanitizerUtil extends ContactStateSanitizerUtil
    {
        public static function getSqlAttributeValueDataAnalyzerType()
        {
            return 'LeadState';
        }

        public static function getBatchAttributeValueDataAnalyzerType()
        {
            return 'LeadState';
        }

        protected static function resolvesValidStateByOrder($stateOrder, $startingOrder)
        {
            if ($stateOrder < $startingOrder)
            {
                return true;
            }
            return false;
        }
    }
?>