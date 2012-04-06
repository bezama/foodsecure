<?php


    /**
     * Sanitizer for relation attributes that are derived (casted down) from a real relation.  Specific for handling
     * account derived attribute values.
     */
    class AccountDerivedIdValueTypeSanitizerUtil extends ModelDerivedIdValueTypeSanitizerUtil
    {
        protected static function getDerivedModelClassName()
        {
            return 'Account';
        }
    }
?>