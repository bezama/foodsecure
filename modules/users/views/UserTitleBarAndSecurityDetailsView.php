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

    class UserTitleBarAndSecurityDetailsView extends GridView
    {
        public function __construct(
            $controllerId,
            $moduleId,
            User $user,
            ModulePermissionsForm $modulePermissionsForm,
            RightsForm $rightsForm,
            PoliciesForm $policiesForm,
            array $modulePermissionsViewMetadata,
            array $rightsViewMetadata,
            array $policiesViewMetadata,
            array $groupMembershipViewData
            )
        {
            parent::__construct(10, 1);
            $menuItems = MenuUtil::getAccessibleShortcutsMenuByCurrentUser('UsersModule');
            $shortcutsMenu = new DropDownShortcutsMenuView(
                $controllerId,
                $moduleId,
                $menuItems
            );
            $titleBar = new TitleBarView (
                                    UsersModule::getModuleLabelByTypeAndLanguage('Plural'),
                                    $user . '&#160;-&#160;' . Yii::t('Default', 'Security') . '&#160;',
                                    1,
                                    $shortcutsMenu->render());
            $this->setView($titleBar, 0, 0);
            $this->setView(new UserSecurityDetailsView($controllerId, $moduleId, $user->id), 1, 0);
            $this->setView(new TitleBarView (Yii::t('Default', 'Groups')), 2, 0);
            $this->setView(new UserGroupMembershipView($controllerId, $moduleId, $groupMembershipViewData, $user->id), 3, 0);
            $this->setView(new TitleBarView (Yii::t('Default', 'Rights')), 4, 0);
            $this->setView(new RightsEditAndDetailsView('Details', $controllerId, $moduleId, $rightsForm, $user->id, $rightsViewMetadata), 5, 0);
            $this->setView(new TitleBarView (Yii::t('Default', 'Policies')), 6, 0);
            $this->setView(new PoliciesEditAndDetailsView('Details', $controllerId, $moduleId, $policiesForm, $user->id, $policiesViewMetadata), 7, 0);
            $this->setView(new TitleBarView (Yii::t('Default', 'Module Permissions')), 8, 0);
            $this->setView(new ModulePermissionsEditAndDetailsView('Details', $controllerId, $moduleId, $modulePermissionsForm, $user->id, $modulePermissionsViewMetadata), 9, 0);
        }
    }
?>