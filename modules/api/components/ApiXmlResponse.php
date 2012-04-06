<?php


    /**
     * ApiXmlResponse
     */
    class ApiXmlResponse extends ApiResponse
    {
        /**
        * Generate service output json format
        * @param ApiResult $result
        */
        public static function generateOutput($result)
        {
            assert('$result instanceof ApiResult');
            throw new NotSupportedException();
            return;
            //$data = $result->convertToArray();
            //$xml = ApiXmlParser::arrayToXml($data);
        }
    }
?>