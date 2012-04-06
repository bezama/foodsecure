<?php


    class AccountDetailsAndRelationsView extends DetailsAndRelationsView
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
                        'viewClassName' => 'AccountEditAndDetailsView',
                    ),
                    'leftBottomView' => array(
                        'showAsTabbed' => true,
                        'columns' => array(
                            array(
                                'rows' => array(
                                    array(
                                        'type' => 'AccountNoteInlineEditAndLatestActivtiesForPortlet'
                                    ),
                                    array(
                                        'type' => 'ContactsForAccountRelatedList',
                                    ),
                                    array(
                                        'type' => 'OpportunitiesForAccountRelatedList',
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
                                        'type' => 'UpcomingMeetingsForAccountRelatedList',
                                    ),
                                    array(
                                        'type' => 'OpenTasksForAccountRelatedList',
                                    )
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