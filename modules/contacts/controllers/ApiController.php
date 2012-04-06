<?php


    /**
    * Contacts API Controller
    */
    class ContactsApiController extends fosaModuleApiController
    {
        protected function getSearchFormClassName()
        {
            return 'ContactsSearchForm';
        }
    }
?>
