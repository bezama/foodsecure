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
     * A class for creating notification messages. A message can then have zero or more
     * notifications attached to it.
     */
    class NotificationMessage extends Item
    {
        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata[__CLASS__] = array(
                'members' => array(
                    'htmlContent',
                    'textContent',
                ),
                'relations' => array(
                    'notifications' => array(RedBeanModel::HAS_MANY, 'Notification'),
                ),
                'rules' => array(
                    array('htmlContent',   'type',    'type' => 'string'),
                    array('textContent',   'type',    'type' => 'string'),
                ),
                'elements' => array(
                    'htmlContent'     => 'TextArea',
                    'textContent'     => 'TextArea',
                ),
                'defaultSortAttribute' => null,
                'noAudit' => array(
                    'htmlContent',
                    'textContent',
                )
            );
            return $metadata;
        }

        public static function isTypeDeletable()
        {
            return true;
        }
    }
?>
