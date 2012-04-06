<?php


    class HomeDashboardView extends DashboardView
    {
        public function isUniqueToAPage()
        {
            return true;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'toolbar' => array(
                        'elements' => array(
                            array('type' => 'EditDashboardLink'),
                            array('type' => 'AddPortletAjaxLink',
                                'uniqueLayoutId' => 'eval:$this->uniqueLayoutId',
                                'ajaxOptions' => array(
                                    'onclick' => '$("#modalContainer").dialog("open"); return false;',
                                    'update' => '#modalContainer',
                                ),
                                'htmlOptions' => array('id' => 'AddPortletLink')
                            ),
                        ),
                    ),
                    'columns' => array(
                        array(
                            'rows' => array(
                                array(
                                    'type' => 'MeetingsMyList',
                                ),
                                array(
                                    'type' => 'TasksMyList',
                                ),
                                array(
                                    'type' => 'OpportunitiesMyList',
                                ),
                                array(
                                    'type' => 'LeadsMyList',
                                ),
                                array(
                                    'type' => 'AccountsMyList',
                                ),
                            )
                        ),
                        array(
                            'rows' => array(
                                array(
                                    'type' => 'OpportunitiesByStageChart',
                                ),
                                array(
                                    'type' => 'OpportunitiesBySourceChart',
                                ),
                                array(
                                    'type' => 'RssReader',
                                ),
                            )
                        )
                    )
                )
            );
            return $metadata;
        }
    }
?>
