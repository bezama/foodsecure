<?php


    class DashboardLayoutTypeStaticDropDownElement extends StaticDropDownFormElement
    {
        /**
         * @see DropDownElement::getDropDownArray()
         */
        protected function getDropDownArray()
        {
            return Dashboard::getLayoutTypesData();
        }
    }
?>