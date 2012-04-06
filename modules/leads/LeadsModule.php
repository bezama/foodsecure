<?php


    class LeadsModule extends SecurableModule
    {
        const CONVERT_NO_ACCOUNT           = 1;
        const CONVERT_ACCOUNT_NOT_REQUIRED = 2;
        const CONVERT_ACCOUNT_REQUIRED     = 3;

        const RIGHT_CREATE_LEADS  = 'Create Leads';
        const RIGHT_DELETE_LEADS  = 'Delete Leads';
        const RIGHT_ACCESS_LEADS  = 'Access Leads Tab';
        const RIGHT_CONVERT_LEADS = 'Convert Leads';

        public function getDependencies()
        {
            return array(
                'accounts',
                'contacts',
                'configuration',
                'fosa',
            );
        }

        public function getRootModelNames()
        {
            return array('LeadsFilteredList');
        }

        public static function getUntranslatedRightsLabels()
        {
            $labels                            = array();
            $labels[self::RIGHT_CREATE_LEADS]  = 'Create LeadsModulePluralLabel';
            $labels[self::RIGHT_DELETE_LEADS]  = 'Delete LeadsModulePluralLabel';
            $labels[self::RIGHT_ACCESS_LEADS]  = 'Access LeadsModulePluralLabel Tab';
            $labels[self::RIGHT_CONVERT_LEADS] = 'Convert LeadsModulePluralLabel';
            return $labels;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array();
            $metadata['global'] = array(
                'convertToAccountSetting' => LeadsModule::CONVERT_ACCOUNT_NOT_REQUIRED,
                'convertToAccountAttributesMapping' => array(
                    'industry'         => 'industry',
                    'website'          => 'website',
                    'primaryAddress'   => 'billingAddress',
                    'secondaryAddress' => 'shippingAddress',
                    'owner'            => 'owner',
                    'officePhone'      => 'officePhone',
                    'officeFax'        => 'officeFax',
                    'companyName'      => 'name',
                ),
                'designerMenuItems' => array(
                    'showFieldsLink'  => true,
                    'showGeneralLink' => true,
                    'showLayoutsLink' => true,
                    'showMenusLink'   => true,
                ),
                'globalSearchAttributeNames' => array(
                    'fullName',
                    'anyEmail',
                    'officePhone',
                    'mobilePhone',
                    'companyName'
                ),
                'tabMenuItems' => array(
                    array(
                        'label' => 'LeadsModulePluralLabel',
                        'url'   => array('/leads/default'),
                        'right' => self::RIGHT_ACCESS_LEADS,
                        'items' => array(
                            array(
                                'label' => 'Create LeadsModuleSingularLabel',
                                'url'   => array('/leads/default/create'),
                                'right' => self::RIGHT_CREATE_LEADS
                            ),
                            array(
                                'label' => 'LeadsModulePluralLabel',
                                'url'   => array('/leads/default'),
                                'right' => self::RIGHT_ACCESS_LEADS
                            ),
                        )
                    ),
                )
            );
            return $metadata;
        }

        public static function getPrimaryModelName()
        {
            return 'Contact';
        }

        public static function getConvertToAccountSetting()
        {
            $metadata = LeadsModule::getMetadata();
            return $metadata['global']['convertToAccountSetting'];
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_LEADS;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_LEADS;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_LEADS;
        }

        public static function getDemoDataMakerClassName()
        {
            return 'LeadsDemoDataMaker';
        }

        public static function getStateMetadataAdapterClassName()
        {
            return 'LeadsStateMetadataAdapter';
        }

        public static function getGlobalSearchFormClassName()
        {
            return 'LeadsSearchForm';
        }
    }
?>
