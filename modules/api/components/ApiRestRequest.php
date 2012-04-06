<?php


    /*
     * ApiResponse
     */
    class ApiRestRequest extends ApiRequest
    {
        /**
         * Return service type.
         * @see ApiRequest::getServiceType()
         */
        public function getServiceType()
        {
            return ApiRequest::REST;
        }

        /**
         * Parse params from request.
         * @return array
         */
        public static function getParamsFromRequest()
        {
            $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
            switch ($requestMethod)
            {
                case 'get':
                    $params = $_GET;
                    break;
                case 'post':
                    $params = $_POST;
                    break;
                case 'put':
                    parse_str(file_get_contents('php://input'), $params);
                    $params['id'] = $_GET['id'];
                    break;
                case 'delete':
                    $params['id'] = $_GET['id'];
                    break;
            }
            return $params;
        }
    }
?>