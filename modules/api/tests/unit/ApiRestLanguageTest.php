<?php


    /**
    * Test language related API functions.
    */
    class ApiRestLanguageTest extends ApiRestTest
    {
        public function testApiServerUrl()
        {
            $this->assertTrue(strlen($this->serverUrl) > 0);
        }

    /**
        * @depends testApiServerUrl
        */
        public function testLanguage()
        {
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;

            $model = new ApiTestModelItem2();
            $model->name = 'sample name';
            $this->assertTrue($model->save());
            $headers = array(
                'Accept: application/json',
                'fosa_API_REQUEST_TYPE: REST',
                'fosa_LANG: fr',
            );
            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/api/testModelItem2/api/read/2/' , 'GET', $headers);
            $response = json_decode($response, true);
            $this->assertEquals(ApiResponse::STATUS_FAILURE, $response['status']);
            $this->assertEquals('Login required.', $response['message']);

            $authenticationData = $this->login();
            $headers = array(
                'Accept: application/json',
                'fosa_SESSION_ID: ' . $authenticationData['sessionId'],
                'fosa_TOKEN: ' . $authenticationData['token'],
                'fosa_API_REQUEST_TYPE: REST',
                'fosa_LANG: fr'
            );

            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/api/testModelItem2/api/read/2/' , 'GET', $headers);
            $response = json_decode($response, true);
            $this->assertEquals(ApiResponse::STATUS_FAILURE, $response['status']);
            $this->assertEquals('ID invalide.', $response['message']);
        }
    }
?>