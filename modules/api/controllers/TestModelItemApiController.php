<?php


    /**
    * Api test controller, used to test actions related to ApiTestModelItem
    */
    class ApiTestModelItemApiController extends fosaModuleApiController
    {
        protected function getModelName()
        {
            return 'ApiTestModelItem';
        }

        protected function getSearchFormClassName()
        {
            return 'ApiTestModelItemSearchForm';
        }
    }
?>
