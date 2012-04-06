<?php


    /**
    * Test Role related API functions.
    */
    class ApiRestRoleTest extends ApiRestTest
    {
        public function testApiServerUrl()
        {
            $this->assertTrue(strlen($this->serverUrl) > 0);
        }

        /**
        * @depends testApiServerUrl
        */
        public function testGetRole()
        {
            $super = User::getByUsername('super');
            Yii::app()->user->userModel        = $super;
            $authenticationData = $this->login();
            $headers = array(
                'Accept: application/json',
                'fosa_SESSION_ID: ' . $authenticationData['sessionId'],
                'fosa_TOKEN: ' . $authenticationData['token'],
                'fosa_API_REQUEST_TYPE: REST',
            );

            $role = new Role();
            $role->name = 'myRole';
            $role->validate();
            $saved = $role->save();
            $this->assertTrue($saved);

            $roles                 = Role::getAll();
            $redBeanModelToApiDataUtil  = new RedBeanModelToApiDataUtil($roles[0]);
            $compareData  = $redBeanModelToApiDataUtil->getData();

            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/fosa/role/api/read/' . $compareData['id'], 'GET', $headers);
            $response = json_decode($response, true);

            $this->assertEquals(ApiResponse::STATUS_SUCCESS, $response['status']);
            $this->assertEquals($compareData, $response['data']);
        }

        /**
        * @depends testGetRole
        */
        public function testListRoles()
        {
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            $authenticationData = $this->login();
            $headers = array(
                'Accept: application/json',
                'fosa_SESSION_ID: ' . $authenticationData['sessionId'],
                'fosa_TOKEN: ' . $authenticationData['token'],
                'fosa_API_REQUEST_TYPE: REST',
            );

            $roles                 = Role::getAll();
            $compareData = array();
            foreach ($roles as $role)
            {
                $redBeanModelToApiDataUtil  = new RedBeanModelToApiDataUtil($role);
                $compareData[] = $redBeanModelToApiDataUtil->getData();
            }

            //Test List
            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/fosa/role/api/list/', 'GET', $headers);
            $response = json_decode($response, true);
            $this->assertEquals(ApiResponse::STATUS_SUCCESS, $response['status']);
            $this->assertEquals(count($roles), count($response['data']['items']));
            $this->assertEquals(count($roles), $response['data']['totalCount']);
            $this->assertEquals(1, $response['data']['currentPage']);
            $this->assertEquals($compareData, $response['data']['items']);
        }
    }
?>