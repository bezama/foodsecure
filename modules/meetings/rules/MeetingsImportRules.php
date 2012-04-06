<?php


    /**
     * Defines the import rules for importing into the meetings module.
     */
    class MeetingsImportRules extends ActivitiesImportRules
    {
        public static function getModelClassName()
        {
            return 'Meeting';
        }
    }
?>