<?php


    /**
     * Specific rules for the meeting model.
     */
    class MeetingMashableActivityRules extends ActivityMashableActivityRules
    {
        /**
         * Only show meetings that have a start date in the past from when the mashable activity feed is run.
         * @see ActivityMashableActivityRules::resolveSearchAttributeDataForLatestActivities()
         */
        protected function resolveSearchAttributeDataForLatestActivities($searchAttributeData)
        {
            assert('is_array($searchAttributeData)');
            $clausesCount = count($searchAttributeData['clauses']);
            $searchAttributeData['clauses'][($clausesCount + 1)] = array(
                    'attributeName'        => 'startDateTime',
                    'operatorType'         => 'lessThan',
                    'value'                => DateTimeUtil::convertTimestampToDbFormatDateTime(time())
            );
            $searchAttributeData['structure'] .= ' and ' . ($clausesCount + 1);
            return $searchAttributeData;
        }
    }
?>