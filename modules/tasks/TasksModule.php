<?php
    /*********************************************************************************
     * fosa is a customer relationship management program developed by
     * fosa, Inc. Copyright (C) 2012 fosa Inc.
     *
     * fosa is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY fosa, fosa DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * fosa is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
     * details.
     *
     * You should have received a copy of the GNU General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact fosa, Inc. with a mailing address at 113 McHenry Road Suite 207,
     * Buffalo Grove, IL 60089, USA. or at email address contact@fosa.com.
     ********************************************************************************/

    class TasksModule extends SecurableModule
    {
        const RIGHT_CREATE_TASKS = 'Create Tasks';
        const RIGHT_DELETE_TASKS = 'Delete Tasks';
        const RIGHT_ACCESS_TASKS = 'Access Tasks';

        public function getDependencies()
        {
            return array(
                'activities'
            );
        }

        public function getRootModelNames()
        {
            return array('Task');
        }

        public static function getUntranslatedRightsLabels()
        {
            $labels                           = array();
            $labels[self::RIGHT_CREATE_TASKS] = 'Create TasksModulePluralLabel';
            $labels[self::RIGHT_DELETE_TASKS] = 'Delete TasksModulePluralLabel';
            $labels[self::RIGHT_ACCESS_TASKS] = 'Access TasksModulePluralLabel';
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
            return 'Task';
        }

        public static function getAccessRight()
        {
            return self::RIGHT_ACCESS_TASKS;
        }

        public static function getCreateRight()
        {
            return self::RIGHT_CREATE_TASKS;
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_TASKS;
        }

        public static function getDemoDataMakerClassName()
        {
            return 'TasksDemoDataMaker';
        }

        public static function hasPermissions()
        {
            return true;
        }
    }
?>
