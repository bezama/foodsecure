<?php


    /**
    * Test model for API: ApiTestModelItem3
    */
    class ApiTestModelItem3 extends OwnedSecurableItem
    {
        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata[__CLASS__] = array(
                'members' => array(
                    'name',
                ),
                'rules' => array(
                    array('name',  'type',   'type' => 'string'),
                    array('name',  'length', 'max' => 32),
                ),
            );
            return $metadata;
        }

        public static function isTypeDeletable()
        {
            return true;
        }

        public static function getModuleClassName()
        {
            return 'ApiModule';
        }
    }
?>
