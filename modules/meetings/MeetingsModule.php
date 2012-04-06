<?php


    class MeetingsModule extends SecurableModule
    {
        const RIGHT_CREATE_MEETINGS = 'Create Meetings';
        const RIGHT_DELETE_MEETINGS = 'Delete Meetings';
        const RIGHT_ACCESS_MEETINGS = 'Access Meetings';

        public function getDependencies()
        {
            return array(
                'activities',
            );
        }

        public function getRootModelNames()
        {
            return array('Meeting');
        }

        public static function getUntranslatedRightsLabels()
        {
            $labels                              = array();
            $labels[self::RIGHT_CREATE_MEETINGS] = 'Create MeetingsModulePluralLabel';
            $labels[self::RIGHT_DELETE_MEETINGS] = 'Delete MeetingsModulePluralLabel';
            $labels[self::RIGHT_ACCESS_MEETINGS] = 'Access MeetingsModulePluralLabel';
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
                    'showMenusLink' => false,
                ),
            );
            return $metadata;
        }

        public static function getPrimaryModelName()
        {
            return 'Meeting';
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_MEETINGS;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_MEETINGS;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_MEETINGS;
        }

        public static function getDefaultDataMakerClassName()
        {
            return 'MeetingsDefaultDataMaker';
        }

        public static function getDemoDataMakerClassName()
        {
            return 'MeetingsDemoDataMaker';
        }

        public static function hasPermissions()
        {
            return true;
        }
    }
?>
