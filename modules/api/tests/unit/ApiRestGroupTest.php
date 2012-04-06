<?php


    /**
    * Test Group related API functions.
    */
    class ApiRestGroupTest extends ApiRestTest
    {
        public function testApiServerUrl()
        {
            $this->assertTrue(strlen($this->serverUrl) > 0);
        }

        /**
        * @depends testApiServerUrl
        */
        public function testGetGroup()
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

            $group = new Group();
            $group->name = 'myGroup';
            $saved = $group->save();
            $this->assertTrue($saved);

            $groups                 = Group::getAll();
            $redBeanModelToApiDataUtil  = new RedBeanModelToApiDataUtil($groups[0]);
            $compareData  = $redBeanModelToApiDataUtil->getData();

            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/fosa/group/api/read/' . $compareData['id'], 'GET', $headers);
            $response = json_decode($response, true);

            $this->assertEquals(ApiResponse::STATUS_SUCCESS, $response['status']);
            $this->assertEquals($compareData, $response['data']);
        }

        /**
        * @depends testGetGroup
        */
        public function testListGroups()
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

            $groups                 = Group::getAll();
            $compareData = array();
            foreach ($groups as $group)
            {
                $redBeanModelToApiDataUtil  = new RedBeanModelToApiDataUtil($group);
                $compareData[] = $redBeanModelToApiDataUtil->getData();
            }

            //Test List
            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/fosa/group/api/list/', 'GET', $headers);
            $response = json_decode($response, true);
            $this->assertEquals(ApiResponse::STATUS_SUCCESS, $response['status']);
            $this->assertEquals(count($groups), count($response['data']['items']));
            $this->assertEquals(count($groups), $response['data']['totalCount']);
            $this->assertEquals(1, $response['data']['currentPage']);
            $this->assertEquals($compareData, $response['data']['items']);
        }
    }
?>