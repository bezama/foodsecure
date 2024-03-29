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

    class NotificationRulesTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            UserTestHelper::createBasicUser('billy');
        }

        public function testAllowDuplicates()
        {
            $rules = new SimpleNotificationRules();
            $this->assertFalse($rules->allowDuplicates());
        }

        public function testSetGetIsCritical()
        {
            $rules = new SimpleNotificationRules();
            $this->assertFalse($rules->isCritical());
            $rules->setCritical(true);
            $this->assertTrue($rules->isCritical());
            $rules->setCritical(false);
            $this->assertFalse($rules->isCritical());
        }

        public function testGetType()
        {
            $rules = new SimpleNotificationRules();
            $this->assertEquals('Simple', $rules->getType());
        }

        public function addAndGetUsers()
        {
            $rules = new SimpleNotificationRules();
            $this->assertEquals(0, $rules->getUsers());
            $rules->addUser(User::getByUsername('billy'));
            $this->assertEquals(1, $rules->getUsers());
            //Try to add same user again.
            $rules->addUser(User::getByUsername('billy'));
            $this->assertEquals(1, $rules->getUsers());
            $rules->addUser(User::getByUsername('super'));
            $this->assertEquals(2, $rules->getUsers());
        }
    }
?>
