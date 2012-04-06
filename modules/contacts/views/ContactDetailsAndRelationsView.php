<?php


    class ContactDetailsAndRelationsView extends DetailsAndRelationsView
    {
        public function isUniqueToAPage()
        {
            return true;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'leftTopView' => array(
                        'viewClassName' => 'ContactEditAndDetailsView',
                    ),
                    'leftBottomView' => array(
                        'showAsTabbed' => true,
                        'columns' => array(
                            array(
                                'rows' => array(
                                    array(
                                        'type' => 'ContactNoteInlineEditAndLatestActivtiesForPortlet'
                                    ),
                                    array(
                                        'type' => 'OpportunitiesForContactRelatedList'
                                    )
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