<?php


    /**
    * Test CustomField related API functions.
    */
    class ApiRestCustomFieldTest extends ApiRestTest
    {
        public function testApiServerUrl()
        {
            $this->assertTrue(strlen($this->serverUrl) > 0);
        }

        /**
        * @depends testApiServerUrl
        */
        public function testListView()
        {
            Yii::app()->user->userModel        = User::getByUsername('super');
            $authenticationData = $this->login();
            $headers = array(
                'Accept: application/json',
                'fosa_SESSION_ID: ' . $authenticationData['sessionId'],
                'fosa_TOKEN: ' . $authenticationData['token'],
                'fosa_API_REQUEST_TYPE: REST',
            );

            $industryValues = array(
                'Automotive',
                'Adult Entertainment',
                'Financial Services',
                'Mercenaries & Armaments',
            );
            $industryFieldData = CustomFieldData::getByName('Industries');
            $industryFieldData->serializedData = serialize($industryValues);
            $this->assertTrue($industryFieldData->save());

            $customFieldDataItems = CustomFieldData::getAll();
            $compareData = array();
            foreach ($customFieldDataItems as $customFieldDataItem)
            {
                $dataAndLabels    = CustomFieldDataUtil::
                    getDataIndexedByDataAndTranslatedLabelsByLanguage($customFieldDataItem, 'en');
                $compareData[$customFieldDataItem->name] = $dataAndLabels;
            }

            //Test List
            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/fosa/customField/api/list/', 'GET', $headers);
            $response = json_decode($response, true);
            $this->assertEquals(ApiResponse::STATUS_SUCCESS, $response['status']);
            $this->assertEquals($compareData, $response['data']);
        }

        /**
        * @depends testApiServerUrl
        */
        public function testGetCustomFieldData()
        {
            $authenticationData = $this->login();
            $headers = array(
                'Accept: application/json',
                'fosa_SESSION_ID: ' . $authenticationData['sessionId'],
                'fosa_TOKEN: ' . $authenticationData['token'],
                'fosa_API_REQUEST_TYPE: REST',
            );

            //Fill some data
            $values = array(
                'Prospect',
                'Customer',
                'Vendor',
            );
            $typeFieldData = CustomFieldData::getByName('AccountTypes');
            $typeFieldData->serializedData = serialize($values);
            $this->assertTrue($typeFieldData->save());

            CustomFieldData::forgetAll();
            $customFieldData = CustomFieldData::getByName('AccountTypes');
            $compareData    = CustomFieldDataUtil::
                getDataIndexedByDataAndTranslatedLabelsByLanguage($customFieldData, 'en');

            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/fosa/customField/api/read/AccountTypes', 'GET', $headers);
            $response = json_decode($response, true);
            $this->assertEquals(ApiResponse::STATUS_SUCCESS, $response['status']);
            $this->assertEquals($compareData, $response['data']);
        }
    }
?>