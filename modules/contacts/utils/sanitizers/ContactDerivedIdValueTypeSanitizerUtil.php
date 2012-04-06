<?php


    /**
     * Sanitizer for relation attributes that are derived (casted down) from a real relation.  Specific for handling
     * contact derived attribute values.
     */
    class ContactDerivedIdValueTypeSanitizerUtil extends ModelDerivedIdValueTypeSanitizerUtil
    {
        protected static function getDerivedModelClassName()
        {
            return 'Contact';
        }
    }
?>