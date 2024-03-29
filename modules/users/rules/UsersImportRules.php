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
     * Defines the import rules for importing into the users module.
     */
    class UsersImportRules extends ImportRules
    {
        /**
         * Override to handle the password setting as well as not showing all the derived types that are available
         * in other models. This is why this override does not call the parent first.
         * @return array
         */
        public static function getDerivedAttributeTypes()
        {
            return array('Password', 'UserStatus');
        }

        /**
         * Override to block out additional attributes that are not importable
         * @return array
         */
        public static function getNonImportableAttributeNames()
        {
            return array_merge(parent::getNonImportableAttributeNames(), array('currency', 'language', 'timeZone',
                               'manager', 'hash', 'createdByUser', 'modifiedByUser',
                               'createdDateTime', 'modifiedDateTime'));
        }

        public static function getModelClassName()
        {
            return 'User';
        }
    }
?>