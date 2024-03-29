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

    /**
     * Form to all editing and viewing of a user's configuration values in the user interface.
     */
    class UserConfigurationForm extends ConfigurationForm
    {
        /**
         * Is set in order to properly route action elements in view.
         */
        public $userId;
        public $listPageSize;
        public $subListPageSize;

        public function __construct($userId)
        {
            assert('is_int($userId) && $userId > 0');
            $this->userId = $userId;
        }

        /**
         * When getId is called, it is looking for the user model id for the user
         * who's configuration values are being edited.
         */
        public function getId()
        {
            return $this->userId;
        }

        public function rules()
        {
            return array(
                array('listPageSize',             'required'),
                array('listPageSize',             'type',      'type' => 'integer'),
                array('listPageSize',             'numerical', 'min' => 1),
                array('subListPageSize',          'required'),
                array('subListPageSize',          'type',      'type' => 'integer'),
                array('subListPageSize',          'numerical', 'min' => 1),
            );
        }

        public function attributeLabels()
        {
            return array(
                'listPageSize'              => Yii::t('Default', 'List page size'),
                'subListPageSize'           => Yii::t('Default', 'Sublist page size'),
            );
        }
    }
?>