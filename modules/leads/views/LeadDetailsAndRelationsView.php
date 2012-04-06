<?php


    class LeadDetailsAndRelationsView extends DetailsAndRelationsView
    {
        /**
         * Declare layout as 2 columns
         */
        protected $layoutType = '75,25'; // Not Coding Standard

        public function isUniqueToAPage()
        {
            return true;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'leftTopView' => array(
                        'viewClassName' => 'LeadEditAndDetailsView',
                    ),
                    'leftBottomView' => array(
                        'showAsTabbed' => false,
                        'columns' => array(
                            array(
                                'rows' => array(
                                    array(
                                        'type' => 'ContactNoteInlineEditAndLatestActivtiesForPortlet'
                                    ),
                                )
                            )
                        )
                    ),
                    'rightTopView' => array(
                        'columns' => array(
                            array(
                                'rows' => array(
                                    array(
                                        'type' => 'UpcomingMeetingsForContactRelatedList',
                                    ),
                                    array(
                                        'type' => 'OpenTasksForContactRelatedList',
                                    ),
                                )
                            )
                        )
                    )
                )
            );
            return $metadata;
        }
    }
?>