<?php


    class JobLogStatusListViewColumnAdapter extends TextListViewColumnAdapter
    {
        public function renderGridViewData()
        {
            return array(
                'name'  => 'status',
                'value' => 'call_user_func("JobLogViewUtil::renderStatusAndMessageListContent", $data)',
                'type'  => 'raw',
            );
        }
    }
?>