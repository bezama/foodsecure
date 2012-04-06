<?php


    /**
     * 'AddPortlet' lets a user add a portlet to a dashboard.
     * Currently uses the RIGHT_CREATE_DASHBOARD right to determine
     * if a user can or cannot add portlets.
     */
    class AddPortletActionSecurity extends ActionSecurity
    {
        protected function getRightToCheck()
        {
            return array('HomeModule', HomeModule::RIGHT_CREATE_DASHBOARDS);
        }
    }
?>