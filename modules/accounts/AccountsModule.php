<?php


    class AccountsModule extends SecurableModule
    {
        const RIGHT_CREATE_ACCOUNTS = 'Create Accounts';
        const RIGHT_DELETE_ACCOUNTS = 'Delete Accounts';
        const RIGHT_ACCESS_ACCOUNTS = 'Access Accounts Tab';

        public function getDependencies()
        {
            return array(
                'configuration',
                'fosa',
            );
        }

        public function getRootModelNames()
        {
            return array('Account', 'AccountsFilteredList');
        }

        public static function getUntranslatedRightsLabels()
        {
            $labels                              = array();
            $labels[self::RIGHT_CREATE_ACCOUNTS] = 'Create AccountsModulePluralLabel';
            $labels[self::RIGHT_DELETE_ACCOUNTS] = 'Delete AccountsModulePluralLabel';
            $labels[self::RIGHT_ACCESS_ACCOUNTS] = 'Access AccountsModulePluralLabel Tab';
            return $labels;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array();
            $metadata['global'] = array(
                'designerMenuItems' => array(
                    'showFieldsLink' => true,
                    'showGeneralLink' => true,
                    'showLayoutsLink' => true,
                    'showMenusLink' => true,
                ),
                'globalSearchAttributeNames' => array(
                    'name',
                    'anyEmail',
                    'officePhone',
                ),
                'tabMenuItems' => array(
                    array(
                        'label' => 'AccountsModulePluralLabel',
                        'url'   => array('/accounts/default'),
                        'right' => self::RIGHT_ACCESS_ACCOUNTS,
                        'items' => array(
                            array(
                                'label' => 'Create AccountsModuleSingularLabel',
                                'url'   => array('/accounts/default/create'),
                                'right' => self::RIGHT_CREATE_ACCOUNTS
                            ),
                            array(
                                'label' => 'AccountsModulePluralLabel',
                                'url'   => array('/accounts/default'),
                                'right' => self::RIGHT_ACCESS_ACCOUNTS
                            ),
                        ),
                    ),
                )
            );
            return $metadata;
        }

        public static function getPrimaryModelName()
        {
            return 'Account';
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_ACCOUNTS;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_ACCOUNTS;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_ACCOUNTS;
        }

        public static function getDefaultDataMakerClassName()
        {
            return 'AccountsDefaultDataMaker';
        }

        public static function getDemoDataMakerClassName()
        {
            return 'AccountsDemoDataMaker';
        }

        public static function getGlobalSearchFormClassName()
        {
            return 'AccountsSearchForm';
        }

        public static function hasPermissions()
        {
            return true;
        }
    }
?>