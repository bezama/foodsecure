<?php


    /**
     * ApiModule is used to manage api calls.
     *
     */
    class ApiModule extends SecurableModule
    {
        public function getDependencies()
        {
            return array(
                'configuration',
                'fosa',
            );
        }

        public function getRootModelNames()
        {
            return array();
        }

        public static function getDefaultMetadata()
        {
            $metadata = array();
            return $metadata;
        }
    }
?>