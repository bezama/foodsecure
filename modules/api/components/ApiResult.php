<?php


    /**
     * ApiResult
     */
    class ApiResult
    {
        /**
         * Result status.
         * @var string
         */
        public $status;

        /**
        * Data array.
        * @var array
        */
        public $data = array();

        /**
        * Response message.
        * @var string
        */
        public $message = null;

        /**
        * Array of errors that happen during request, for example list of all validation errors during model saving.
        * @var array
        */
        public $errors = null;

        /**
         * Constructor
         * @param string $status
         * @param array $data
         * @param string $message
         * @param array $errors
         */
        public function __construct($status, $data, $message = null, $errors = null)
        {
            $this->status   = $status;
            $this->data     = $data;
            $this->message  = $message;
            $this->errors   = $errors;
        }

        /**
         * Is result status sucessful or not.
         * @return boolean
         */
        public function isStatusSuccess()
        {
            if (isset($this->status) && $this->status == ApiResponse::STATUS_SUCCESS)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        /**
         * Convert ApiResult object into array.
         * @return array
         */
        public function convertToArray()
        {
            $result = array(
                'status'  => $this->status,
                'data'    => $this->data,
                'message' => $this->message,
                'errors'  => $this->errors,
            );
            return $result;
        }
    }
?>
