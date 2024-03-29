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
     * Base view for rendering a list of groups the user is a
     * member of.
     */
    class UserGroupMembershipView extends MetadataView
    {
        protected $controllerId;

        protected $moduleId;

        protected $groupMembership;

        protected $userId;

        public function __construct($controllerId, $moduleId, array $groupMembership, $userId)
        {
            assert('is_string($controllerId) && $controllerId != null');
            assert('is_string($moduleId) && $moduleId != null');
            assert('is_int($userId) && $controllerId != null');
            $this->controllerId           = $controllerId;
            $this->moduleId               = $moduleId;
            $this->groupMembership        = $groupMembership;
            $this->userId                 = $userId;
        }

        protected function renderContent()
        {
            $content  = '<div class="view-toolbar">';
            $content .= '</div>';
            $content .= '<table>';
            $content .= '<colgroup>';
            //$content .= '<col style="width:80%" /><col style="width:20%" />';
            $content .= '<col style="width:100%" />';
            $content .= '</colgroup>';
            $content .= '<tbody>';
            $content .= '<tr><th>' . Yii::t('Default', 'Group') . '</th></tr>'; //<th></th>
            foreach ($this->groupMembership as $groupId => $information)
            {
                $content .= '<tr>';
                $content .= '<td>';
                $content .= $information['displayName'];
                $content .= '</td>';
                if ($information['canRemoveFrom'])
                {
                    $route = $this->moduleId . '/' . $this->controllerId . '/AttributeDetails/';
                    $removeLinkContent = CHtml::link(Yii::t('Default', 'Remove'), Yii::app()->createUrl($route,
                        array(
                            'groupId' => $groupId
                        )
                        //&#160
                    ));
                }
                else
                {
                    $removeLinkContent = null;
                }
                //$content .= '<td>' . $removeLinkContent . '</td>';
                $content .= '</tr>';
            }
            $content .= '</tbody>';
            $content .= '</table>';
            return $content;
        }

        public function isUniqueToAPage()
        {
            return false;
        }
    }
?>