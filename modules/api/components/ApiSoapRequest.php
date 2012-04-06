<?php


    /**
     * ApiSoapRequest
     */
    class ApiSoapRequest extends ApiRequest
    {
        /**
        * Return service type.
        * @see ApiRequest::getServiceType()
        */
        public function getServiceType()
        {
            return ApiRequest::SOAP;
        }

        /**
        * Parse params from request.
        * @return array
        */
        public static function getParamsFromRequest()
        {
            throw new NotSupportedException();
            //$params = ApiXmlParser::toArray($xml);
        }
    }
?>