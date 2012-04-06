<?php


    /**
     * ApiResponse
     */
    abstract class ApiResponse
    {
        const STATUS_SUCCESS           = 'SUCCESS';
        const STATUS_FAILURE           = 'FAILURE';

        /**
         * Generate output
         * @param ApiResult $result
         * @throws NotImplementedException
         */
        public static function generateOutput($result)
        {
            throw new NotImplementedException();
        }
    }
?>