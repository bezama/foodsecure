<?php


    /**
    * Test model for API: ApiTestModelItem2
    */
    class ApiTestModelItem2 extends OwnedSecurableItem
    {
        public static function getByName($name)
        {
            assert('is_string($name) && $name != ""');
            return self::getSubset(null, null, null, "name = '$name'");
        }

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
