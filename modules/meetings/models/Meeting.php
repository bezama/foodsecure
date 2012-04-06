<?php


    class Meeting extends MashableActivity
    {
        public function __toString()
        {
            try
            {
                if (trim($this->name) == '')
                {
                    return Yii::t('Default', '(Unnamed)');
                }
                return $this->name;
            }
            catch (AccessDeniedSecurityException $e)
            {
                return '';
            }
        }

        public static function getModuleClassName()
        {
            return 'MeetingsModule';
        }

        /**
         * Returns the display name for the model class.
         * @return dynamic label name based on module.
         */
        protected static function getLabel()
        {
            return 'MeetingsModuleSingularLabel';
        }

        /**
         * Returns the display name for plural of the model class.
         * @return dynamic label name based on module.
         */
        protected static function getPluralLabel()
        {
            return 'MeetingsModulePluralLabel';
        }

        public static function canSaveMetadata()
        {
            return true;
        }

        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata[__CLASS__] = array(
                'members' => array(
                    'description',
                    'endDateTime',
                    'location',
                    'name',
                    'startDateTime',
                ),
                'rules' => array(
                    array('description',      'type', 'type' => 'string'),
                    array('endDateTime',      'type', 'type' => 'datetime'),
                    array('endDateTime',      'RedBeanModelCompareDateTimeValidator', 'type' => 'after',
                                              'compareAttribute' => 'startDateTime'),
                    array('location',         'type',    'type' => 'string'),
                    array('location',         'length',  'min'  => 3, 'max' => 64),
                    array('name',             'required'),
                    array('name',             'type',    'type' => 'string'),
                    array('name',             'length',  'min'  => 3, 'max' => 64),
                    array('startDateTime',    'required'),
                    array('startDateTime',    'type', 'type' => 'datetime'),
                    array('startDateTime',    'RedBeanModelCompareDateTimeValidator', 'type' => 'before',
                                              'compareAttribute' => 'endDateTime'),
                ),
                'relations' => array(
                    'category'             => array(RedBeanModel::HAS_ONE, 'OwnedCustomField', RedBeanModel::OWNED),
                ),
                'elements' => array(
                    'endDateTime'   => 'DateTime',
                    'startDateTime' => 'DateTime',
                ),
                'customFields' => array(
                    'category'     => 'MeetingCategories',
                ),
                'defaultSortAttribute' => 'name',
                'noAudit' => array(
                    'description'
                ),
            );
            return $metadata;
        }

        protected function untranslatedAttributeLabels()
        {
            return array_merge(parent::untranslatedAttributeLabels(),
                array(
                    'endDateTime'   => 'End Time',
                    'startDateTime' => 'Start Time',
                )
            );
        }

        public static function isTypeDeletable()
        {
            return true;
        }

        public static function getMashableActivityRulesType()
        {
            return 'Meeting';
        }

        protected function beforeSave()
        {
            if (parent::beforeSave())
            {
                if (array_key_exists('startDateTime', $this->originalAttributeValues) &&
                    $this->startDateTime != null)
                {
                    $this->unrestrictedSet('latestDateTime', $this->startDateTime);
                }
                return true;
            }
            else
            {
                return false;
            }
        }

        public static function hasReadPermissionsOptimization()
        {
            return true;
        }
    }
?>
