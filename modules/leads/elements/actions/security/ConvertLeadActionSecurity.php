<?php


    /**
     * 'ConvertLead' takes the user to a form
     * where they can convert a contact to a different
     * contact state.
     */
    class ConvertLeadActionSecurity extends ActionSecurity
    {
        protected function getRightToCheck()
        {
            return array('LeadsModule', LeadsModule::RIGHT_CONVERT_LEADS);
        }

        protected function getPermissionToCheck()
        {
            return Permission::WRITE;
        }
    }
?>