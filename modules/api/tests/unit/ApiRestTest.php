<?php


    /**
    * Base class for API REST functions tests.
    */
    class ApiRestTest extends ApiBaseTest
    {
        protected function login($username = 'super', $password = 'super')
        {
            $headers = array(
                'Accept: application/json',
                'fosa_AUTH_USERNAME: ' . $username,
                'fosa_AUTH_PASSWORD: ' . $password,
                'fosa_API_REQUEST_TYPE: REST',
            );
            $response = ApiRestTestHelper::createApiCall($this->serverUrl . '/test.php/fosa/api/login', 'POST', $headers);
            $response = json_decode($response, true);

            if ($response['status'] == ApiResponse::STATUS_SUCCESS)
            {
                return $response['data'];
            }
            else
            {
                return false;
            }
        }
    }
?>