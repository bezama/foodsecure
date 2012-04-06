<?php


    class JobLogsModalListView extends ListView
    {
        public function __construct($controllerId, $moduleId, $modelClassName, $dataProvider, $gridIdSuffix = null)
        {
            parent::__construct($controllerId, $moduleId, $modelClassName, $dataProvider, array(), false, $gridIdSuffix);
            $this->rowsAreSelectable = false;
        }

        /**
         * Override to remove action buttons.
         */
        protected function getCGridViewLastColumn()
        {
            return array();
        }

        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'panels' => array(
                        array(
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'startDateTime', 'type' => 'DateTime',
                                                      'htmlOptions' => array('nowrap' => 'nowrap')),
                                            ),
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'endDateTime', 'type' => 'DateTime',
                                                      'htmlOptions' => array('nowrap' => 'nowrap')),
                                            ),
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'status', 'type' => 'JobLogStatus'),
                                            ),
                                        ),
                                    )
                                ),
                            ),
                        ),
                    ),
                ),

            );
            return $metadata;
        }
    }
?>
