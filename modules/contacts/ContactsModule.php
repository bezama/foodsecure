<?php


    class ContactsModule extends SecurableModule
    {
        const RIGHT_CREATE_CONTACTS = 'Create Contacts';
        const RIGHT_DELETE_CONTACTS = 'Delete Contacts';
        const RIGHT_ACCESS_CONTACTS = 'Access Contacts Tab';

        public function getDependencies()
        {
            return array(
                'configuration',
                'fosa',
            );
        }

        public function getRootModelNames()
        {
            return array('Contact', 'ContactsFilteredList');
        }

        public static function getUntranslatedRightsLabels()
        {
            $labels                              = array();
            $labels[self::RIGHT_CREATE_CONTACTS] = 'Create ContactsModulePluralLabel';
            $labels[self::RIGHT_DELETE_CONTACTS] = 'Delete ContactsModulePluralLabel';
            $labels[self::RIGHT_ACCESS_CONTACTS] = 'Access ContactsModulePluralLabel Tab';
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
                    'fullName',
                    'anyEmail',
                    'officePhone',
                    'mobilePhone',
                ),
                'startingState' => 1,
                'tabMenuItems' => array(
                    array(
                        'label' => 'ContactsModulePluralLabel',
                        'url'   => array('/contacts/default'),
                        'right' => self::RIGHT_ACCESS_CONTACTS,
                        'items' => array(
                            array(
                                'label' => 'Create ContactsModuleSingularLabel',
                                'url'   => array('/contacts/default/create'),
                                'right' => self::RIGHT_CREATE_CONTACTS
                            ),
                            array(
                                'label' => 'ContactsModulePluralLabel',
                                'url'   => array('/contacts/default'),
                                'right' => self::RIGHT_ACCESS_CONTACTS
                            ),
                        ),
                    ),
                )
            );
            return $metadata;
        }

        public static function getPrimaryModelName()
        {
            return 'Contact';
        }

        /**
         * Used on first load to install ContactState data
         * and the startingState for the Contacts module.
         * @return true/false if data was in fact loaded.
         */
        public static function loadStartingData()
        {
            if (count(ContactState::GetAll()) != 0)
            {
                return false;
            }
            $data = array(
                'New',
                'In Progress',
                'Recycled',
                'Dead',
                'Qualified',
                'Customer'
            );
            $order = 0;
            $startingStateId = null;
            foreach ($data as $stateName)
            {
                $state        = new ContactState();
                $state->name  = $stateName;
                $state->order = $order;
                $saved        = $state->save();
                assert('$saved');
                if ($stateName == 'Qualified')
                {
                    $startingStateId = $state->id;
                }
                $order++;
            }
            if ($startingStateId == null)
            {
                throw new NotSupportedException();
            }
            $metadata = ContactsModule::getMetadata();
            $metadata['global']['startingStateId'] = $startingStateId;
            ContactsModule::setMetadata($metadata);
            assert('count(ContactState::GetAll()) == 6');
            return true;
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_CONTACTS;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_CONTACTS;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_CONTACTS;
        }

        /**
         * Override since the ContactsModule controls module permissions for both leads and contacts.
         */
        public static function getSecurableModuleDisplayName()
        {
            $label = static::getModuleLabelByTypeAndLanguage('Plural') . '&#160;&#38;&#160;' .
                     LeadsModule::getModuleLabelByTypeAndLanguage('Plural');
            return $label;
        }

        public static function getDefaultDataMakerClassName()
        {
            return 'ContactsDefaultDataMaker';
        }

        public static function getDemoDataMakerClassName()
        {
            return 'ContactsDemoDataMaker';
        }

        public static function getStateMetadataAdapterClassName()
        {
            return 'ContactsStateMetadataAdapter';
        }

        public static function getGlobalSearchFormClassName()
        {
            return 'ContactsSearchForm';
        }

        public static function hasPermissions()
        {
            return true;
        }
    }
?>
