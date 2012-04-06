<?php


    class Account extends OwnedSecurableItem
    {
        public static function getByName($name)
        {
            assert('is_string($name) && $name != ""');
            return self::getSubset(null, null, null, "name = '$name'");
        }

        protected function untranslatedAttributeLabels()
        {
            return array_merge(parent::untranslatedAttributeLabels(),
                array(
                    'account'       => 'Parent AccountsModuleSingularLabel',
                    'contacts'      => 'ContactsModulePluralLabel',
                    'opportunities' => 'OpportunitiesModulePluralLabel',
                )
            );
        }

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
            return 'AccountsModule';
        }

        /**
         * Returns the display name for the model class.
         * @return dynamic label name based on module.
         */
        protected static function getLabel()
        {
            return 'AccountsModuleSingularLabel';
        }

        /**
         * Returns the display name for plural of the model class.
         * @return dynamic label name based on module.
         */
        protected static function getPluralLabel()
        {
            return 'AccountsModulePluralLabel';
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
                    'annualRevenue',
                    'description',
                    'employees',
                    'name',
                    'officePhone',
                    'officeFax',
                    'website',
                ),
                'relations' => array(
                    'account'          => array(RedBeanModel::HAS_MANY_BELONGS_TO,  'Account'),
                    'accounts'         => array(RedBeanModel::HAS_MANY,             'Account'),
                    'billingAddress'   => array(RedBeanModel::HAS_ONE,              'Address',          RedBeanModel::OWNED),
                    'contacts'         => array(RedBeanModel::HAS_MANY,             'Contact'),
                    'industry'         => array(RedBeanModel::HAS_ONE,              'OwnedCustomField', RedBeanModel::OWNED),
                    'opportunities'    => array(RedBeanModel::HAS_MANY,             'Opportunity'),
                    'primaryEmail'     => array(RedBeanModel::HAS_ONE,              'Email',            RedBeanModel::OWNED),
                    'secondaryEmail'   => array(RedBeanModel::HAS_ONE,              'Email',            RedBeanModel::OWNED),
                    'shippingAddress'  => array(RedBeanModel::HAS_ONE,              'Address',          RedBeanModel::OWNED),
                    'type'             => array(RedBeanModel::HAS_ONE,              'OwnedCustomField', RedBeanModel::OWNED),
                ),
                'rules' => array(
                    array('annualRevenue', 'type',    'type' => 'float'),
                    array('description',   'type',    'type' => 'string'),
                    array('employees',     'type',    'type' => 'integer'),
                    array('name',          'required'),
                    array('name',          'type',    'type' => 'string'),
                    array('name',          'length',  'min'  => 3, 'max' => 64),
                    array('officePhone',   'type',    'type' => 'string'),
                    array('officePhone',   'length',  'min'  => 1, 'max' => 14),
                    array('officeFax',     'type',    'type' => 'string'),
                    array('officeFax',     'length',  'min'  => 1, 'max' => 14),
                    array('website',       'url'),
                ),
                'elements' => array(
                    'account'         => 'Account',
                    'billingAddress'  => 'Address',
                    'description'     => 'TextArea',
                    'officePhone'     => 'Phone',
                    'officeFax'       => 'Phone',
                    'primaryEmail'    => 'EmailAddressInformation',
                    'secondaryEmail'  => 'EmailAddressInformation',
                    'shippingAddress' => 'Address',
                ),
                'customFields' => array(
                    'industry' => 'Industries',
                    'type'     => 'AccountTypes',
                ),
                'defaultSortAttribute' => 'name',
                'rollupRelations' => array(
                    'accounts' => array('contacts', 'opportunities'),
                    'contacts',
                    'opportunities'
                ),
                'noAudit' => array(
                    'annualRevenue',
                    'description',
                    'employees',
                    'website',
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
            return 'Account';
        }

        public static function hasReadPermissionsOptimization()
        {
            return true;
        }
    }
?>