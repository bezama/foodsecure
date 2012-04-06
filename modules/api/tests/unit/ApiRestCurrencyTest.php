<?php


    /**
    * Test Currency related API functions.
    */
    class ApiRestCurrencyTest extends ApiRestTest
    {
        public function testApiServerUrl()
        {
            $this->assertTrue(strlen($this->serverUrl) > 0);
        }

        /**
        * @depends testApiServerUrl
        */
        public function testGetCurrency()
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

            $currencies                 = Currency::getAll();
            $redBeanModelToApiDataUtil  = new RedBeanModelToApiDataUtil($currencies[0]);
            $compareData  = $redBeanModelToApiDataUtil->getData();

            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/fosa/currency/api/read/' . $compareData['id'], 'GET', $headers);
            $response = json_decode($response, true);
            $this->assertEquals(ApiResponse::STATUS_SUCCESS, $response['status']);
            $this->assertEquals($compareData, $response['data']);
        }

        /**
        * @depends testApiServerUrl
        */
        public function testListCurrencies()
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

            $currencies                 = Currency::getAll();
            $compareData = array();
            foreach ($currencies as $currency)
            {
                $redBeanModelToApiDataUtil  = new RedBeanModelToApiDataUtil($currency);
                $compareData[] = $redBeanModelToApiDataUtil->getData();
            }

            //Test List
            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/fosa/currency/api/list/', 'GET', $headers);
            $response = json_decode($response, true);
            $this->assertEquals(ApiResponse::STATUS_SUCCESS, $response['status']);
            $this->assertEquals(count($currencies), count($response['data']['items']));
            $this->assertEquals(count($currencies), $response['data']['totalCount']);
            $this->assertEquals(1, $response['data']['currentPage']);
            $this->assertEquals($compareData, $response['data']['items']);
        }
    }
?>