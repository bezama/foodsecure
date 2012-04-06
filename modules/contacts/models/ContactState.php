<?php


    /**
     * Stores state information for Contacts.
     */
    class ContactState extends RedBeanModel
    {
        public static function getByName($name)
        {
            assert('is_string($name) && $name != ""');
            return self::makeModels(RedBean_Plugin_Finder::where('contactstate', "name = '$name'"));
        }

        public function __toString()
        {
            if (trim($this->name) == '')
            {
                return Yii::t('Default', '(Unnamed)');
            }
            return $this->name;
        }

        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata[__CLASS__] = array(
                'members' => array(
                    'name',
                    'order',
                    'serializedLabels',
                ),
                'relations' => array(
                ),
                'rules' => array(
                    array('name',             'required'),
                    array('name',             'type',   'type' => 'string'),
                    array('name',             'length', 'min'  => 3, 'max' => 64),
                    array('order',            'required'),
                    array('order',            'type',    'type' => 'integer'),
                    array('order',            'numerical', 'min' => 0),
                    array('serializedLabels', 'type', 'type' => 'string'),
                ),
                'defaultSortAttribute' => 'name',
            );
            return $metadata;
        }
    }
?>
