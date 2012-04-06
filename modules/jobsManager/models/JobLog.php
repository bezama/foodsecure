<?php


    /**
     * A class to store historical information on past jobs that have run.
     */
    class JobLog extends Item
    {
        /**
         * Utilized by the status attribute to define the status as complete without an error.
         * @var integer
         */
        const STATUS_COMPLETE_WITHOUT_ERROR = 1;

        /**
         * Utilized by the status attribute to define the status as complet with an error.
         * @var integer
         */
        const STATUS_COMPLETE_WITH_ERROR    = 2;

        public function __toString()
        {
            if ($this->type == null)
            {
                return null;
            }
            return JobsUtil::resolveStringContentByType($this->type);
        }

        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata[__CLASS__] = array(
                'members' => array(
                    'endDateTime',
                    'isProcessed',
                    'message',
                    'startDateTime',
                    'status',
                    'type'
                ),
                'rules' => array(
                    array('endDateTime',    'required'),
                    array('endDateTime',    'type', 'type' => 'datetime'),
                    array('isProcessed',    'boolean'),
                    array('isProcessed',    'validateIsProcessedIsSet'),
                    array('message',        'type',   'type' => 'string'),
                    array('startDateTime',  'required'),
                    array('status',         'required'),
                    array('status',         'type',   'type' => 'integer'),
                    array('startDateTime',  'type', 'type' => 'datetime'),
                    array('type',           'required'),
                    array('type',           'type',   'type' => 'string'),
                    array('type',           'length', 'min'  => 3, 'max' => 64),
                ),
                'defaultSortAttribute' => 'type',
                'noAudit' => array(
                    'endDateTime',
                    'message',
                    'startDateTime',
                    'status',
                    'type'
                ),
                'elements' => array(
                    'description'     => 'TextArea',
                    'endDateTimex'    => 'DateTime',
                    'startDateTime'   => 'DateTime',
                ),
            );
            return $metadata;
        }

        /**
         * Because isProcessed is a boolean attribute, disallow if the value is not specified. We do
         * not want NULL values in the database for this attribute.
         */
        public function validateIsProcessedIsSet()
        {
            if ($this->isProcessed == null)
            {
                $this->addError('isProcessed', Yii::t('Default', 'Is Processed must be set as true or false, not null.'));
            }
        }

        public static function isTypeDeletable()
        {
            return true;
        }
    }
?>