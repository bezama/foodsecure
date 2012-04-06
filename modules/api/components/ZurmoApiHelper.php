<?php


    /**
     * Api helper class
     */
    class fosaApiHelper extends CApplicationComponent
    {
        /**
         * Get params from request, depending on request type(REST or SOAP)
         */
        public function getRequestParams()
        {
            $requestClassName = $this->getRequestClassName();
            $params = $requestClassName::getParamsFromRequest();
            return $params;
        }

        /**
         * Generate response
         * @param ApiResult $result
         */
        public function sendResponse(ApiResult $result)
        {
            $responseClassName = $this->getResponseClassName();
            $responseClassName::generateOutput($result);
        }

        /**
         * Get request class name
         * @throws ApiException
         * @return string
         */
        protected function getRequestClassName()
        {
            $requestType = Yii::app()->apiRequest->getRequestType();
            if ($requestType == ApiRequest::REST)
            {
                return 'ApiRestRequest';
            }
            elseif ($requestType == ApiRequest::SOAP)
            {
                return 'ApiSoapRequest';
            }
            else
            {
                $message = Yii::t('Default', 'Invalid API request type.');
                throw new ApiException($message);
            }
        }

        /**
         * Get response class name
         * @throws ApiException
         * @return string
         */
        protected function getResponseClassName()
        {
            $responseType = Yii::app()->apiRequest->getResponseFormat();
            if ($responseType == ApiRequest::JSON_FORMAT)
            {
                return 'ApiJsonResponse';
            }
            elseif ($responseType == ApiRequest::XML_FORMAT)
            {
                return 'ApiXmlResponse';
            }
            else
            {
                $message = Yii::t('Default', 'Invalid response type.');
                throw new ApiException($message);
            }
        }
    }
?>
