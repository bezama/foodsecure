<?php


    /**
    * Render JSON response.
    */
    class ApiJsonResponse extends ApiResponse
    {
        /**
         * Generate service output json format
         * @param ApiResult $result
         */
        public static function generateOutput($result)
        {
            assert('$result instanceof ApiResult');
            $output = $result->convertToArray();
            echo json_encode($output);
            return;
        }
    }
?>