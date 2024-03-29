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

    class UserGroupMembershipToViewAdapterTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function setUp()
        {
            parent::setUp();
            Yii::app()->user->userModel = User::getByUsername('super');
        }

        public function testPasswordExpiresPolicyRules()
        {
            $everyoneGroup = Group::getByName(Group::EVERYONE_GROUP_NAME);
            $everyoneGroup->save();
            $user = UserTestHelper::createBasicUser('Bobby');
            $id = $user->id;
            unset($user);
            $user = User::getById($id);
            $adapter = new UserGroupMembershipToViewAdapter($user);
            $viewData = $adapter->getViewData();

            $compareData = array(
                $everyoneGroup->id  => array(
                    'displayName'   => 'Everyone',
                    'canRemoveFrom' => false,
                ),
            );
            $this->assertEquals($compareData, $viewData);
            $a = new Group();
            $a->name = 'AAA';
            $this->assertTrue($a->save());
            $a->users->add($user);
            $this->assertTrue($a->save());
            $user->forget();
            $groupId = $a->id;
            $a->forget();
            unset($a);

            $user = User::getById($id);
            $adapter = new UserGroupMembershipToViewAdapter($user);
            $viewData = $adapter->getViewData();
            $compareData = array(
                $everyoneGroup->id  => array(
                    'displayName'   => 'Everyone',
                    'canRemoveFrom' => false,
                ),
                $groupId => array(
                    'displayName'   => 'AAA',
                    'canRemoveFrom' => true,
                )
            );
            $this->assertEquals($compareData, $viewData);
            $user->forget();
            unset($user);
        }
    }
?>
