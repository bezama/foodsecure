<?php


    class Contact extends Person
    {
        public static function getByName($name)
        {
            return fosaModelSearch::getModelsByFullName('Contact', $name);
        }

        protected function untranslatedAttributeLabels()
        {
            return array_merge(parent::untranslatedAttributeLabels(),
                array(
                    'state'         => 'Status',
                    'account'       => 'AccountsModuleSingularLabel',
                    'opportunities' => 'OpportunitiesModulePluralLabel'
                )
            );
        }

        public static function getModuleClassName()
        {
            return 'ContactsModule';
        }

        /**
         * Returns the display name for the model class.
         * @return dynamic label name based on module.
         */
        protected static function getLabel()
        {
            return 'ContactsModuleSingularLabel';
        }

        /**
         * Returns the display name for plural of the model class.
         * @return dynamic label name based on module.
         */
        protected static function getPluralLabel()
        {
            return 'ContactsModulePluralLabel';
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
                    'companyName',
                    'description',
                    'website',
                ),
                'relations' => array(
                    'account'          => array(RedBeanModel::HAS_ONE,   'Account'),
                    'industry'         => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED),
                    'opportunities'    => array(RedBeanModel::MANY_MANY, 'Opportunity'),
                    'secondaryAddress' => array(RedBeanModel::HAS_ONE,   'Address',          RedBeanModel::OWNED),
                    'secondaryEmail'   => array(RedBeanModel::HAS_ONE,   'Email',            RedBeanModel::OWNED),
                    'source'           => array(RedBeanModel::HAS_ONE,   'OwnedCustomField', RedBeanModel::OWNED),
                    'state'            => array(RedBeanModel::HAS_ONE,   'ContactState'),
                ),
                'rules' => array(
                    array('companyName',      'type',    'type' => 'string'),
                    array('companyName',      'length',  'min'  => 3, 'max' => 64),
                    array('description',      'type',    'type' => 'string'),
                    array('state',            'required'),
                    array('website',          'url'),
                ),
                'elements' => array(
                    'account'          => 'Account',
                    'description'      => 'TextArea',
                    'secondaryEmail'   => 'EmailAddressInformation',
                    'secondaryAddress' => 'Address',
                    'state'            => 'ContactState',
                ),
                'customFields' => array(
                    'industry' => 'Industries',
                    'source'   => 'LeadSources',
                ),
                'defaultSortAttribute' => 'lastName',
                'rollupRelations' => array(
                    'opportunities',
                ),
                'noAudit' => array(
                    'description',
                    'website'
                ),
            );
            return $metadata;
        }

        public static function isTypeDeletable()
        {
            return true;
        }

        public static function getRollUpRulesType()
        {
            return 'Contact';
        }

        public static function hasReadPermissionsOptimization()
        {
            return true;
        }
    }
?>
