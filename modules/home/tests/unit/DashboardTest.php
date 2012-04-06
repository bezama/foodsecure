<?php


    class DashboardTest extends BaseTest
    {
        public function testGetNextLayoutId()
        {
            $this->assertEquals(2, Dashboard::getNextLayoutId());
            $user = UserTestHelper::createBasicUser('Billy');

            Yii::app()->user->userModel = User::getByUsername('billy');

            for ($i = 1; $i <= 3; $i++)
            {
                $dashboard = new Dashboard();
                $dashboard->name       = "Dashboard $i";
                $dashboard->layoutId   = $i;
                $dashboard->owner      = $user;
                $dashboard->layoutType = '100';
                $dashboard->isDefault  = false;
                $this->assertTrue($dashboard->save());
            }
            $this->assertEquals(4, Dashboard::getNextLayoutId());
        }

        /**
         * @depends testGetNextLayoutId
         */
        public function testGetByLayoutId()
        {
            $user = User::getByUserName('billy');
            Yii::app()->user->userModel = $user;
            for ($i = 1; $i <= 3; $i++)
            {
                $dashboard = Dashboard::getByLayoutId($i);
                $this->assertEquals($i,             $dashboard->layoutId);
                $this->assertEquals("Dashboard $i", $dashboard->name);
                $this->assertEquals($user->id,      $dashboard->owner->id);
                $this->assertEquals('100',          $dashboard->layoutType);
                $this->assertEquals(0,      $dashboard->isDefault);
            }
        }

        /**
         * @depends testGetByLayoutId
         * @expectedException NotFoundException
         */
        public function testGetByLayoutIdForNonexistentId()
        {
            $dashboard = Dashboard::getByLayoutId(123123);
        }

        /**
         * @depends testGetNextLayoutId
         */
        public function testGetByLayoutIdAndUserId()
        {
            $user = User::getByUserName('billy');
            Yii::app()->user->userModel = $user;
            for ($i = 1; $i <= 3; $i++)
            {
                $dashboard = Dashboard::getByLayoutIdAndUser($i, $user);
                $this->assertEquals($i,             $dashboard->layoutId);
                $this->assertEquals("Dashboard $i", $dashboard->name);
                $this->assertEquals('100',          $dashboard->layoutType);
            }
        }

        /**
         * testGetNextLayoutId
         */
        public function testGetRowsByUserId()
        {
            $user = User::getByUserName('billy');
            Yii::app()->user->userModel = $user;
            $rows = Dashboard::getRowsByUserId($user->id);
            $this->assertEquals(3, count($rows));
            for ($i = 1; $i <= 3; $i++)
            {
                $this->assertEquals("Dashboard $i", $rows[$i - 1]['name']);
                $this->assertEquals($i,             $rows[$i - 1]['layoutId']);
            }
        }

        /**
         * testGetRowsByUserId
         */
        public function testGetRowsByNonexistentUserId()
        {
            $rows = Dashboard::getRowsByUserId(123123);
            $this->assertEquals(0, count($rows));
        }

        /**
         * testGetRowsByUserId
         */
        public function testDeleteDashboardAndRelatedPortlets()
        {
            Yii::app()->user->userModel = User::getByUsername('billy');
            $dashboardCount = count(Dashboard::getAll());
            $this->assertTrue($dashboardCount > 0);
            $user = User::getByUserName('billy');
            Yii::app()->user->userModel = $user;
            $dashboard = new Dashboard();
            $dashboard->name       = "Dashboard TESTING";
            $dashboard->layoutId   = 3;
            $dashboard->owner      = $user;
            $dashboard->layoutType = '100';
            $dashboard->isDefault  = false;
            $this->assertTrue($dashboard->save());
            $this->assertEquals(count(Portlet::getAll()), 0);
            $this->assertEquals(count(Dashboard::getAll()), ($dashboardCount + 1));
            for ($i = 1; $i <= 3; $i++)
            {
                $portlet = new Portlet();
                $portlet->column    = 1;
                $portlet->position  = 1 + $i;
                $portlet->layoutId  = 'TEST' . $dashboard->layoutId;
                $portlet->collapsed = false;
                $portlet->viewType  = 'TasksMyList';
                $portlet->user      = $user;
                $this->assertTrue($portlet->save());
            }
            $this->assertEquals(count(Portlet::getAll()), 3);
            $portlets = Portlet::getByLayoutIdAndUserSortedById('TEST' . $dashboard->layoutId, $user->id);
            foreach ($portlets as $portlet)
            {
                $portlet->delete();
            }
            $dashboard->delete();
            $this->assertEquals(count(Portlet::getAll()), 0);
            $this->assertEquals(count(Dashboard::getAll()), ($dashboardCount));
        }

        /**
         * testGetNextLayoutId
         */

        public function testCreateDashboardFromPost()
        {
            $user = User::getByUserName('billy');
            Yii::app()->user->userModel = $user;
            $dashboard = new Dashboard();
            $dashboard->owner    = $user;
            $dashboard->layoutId = Dashboard::getNextLayoutId();
            $fakePost = array(
                'name'       => 'abc123',
                'layoutType' => '50,50', // Not Coding Standard
            );
            $dashboard->setAttributes($fakePost);
            $dashboard->validate();
            $this->assertEquals(array(), $dashboard->getErrors());
            $this->assertTrue($dashboard->save());
        }
    }
?>
