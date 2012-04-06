<?php


    /**
     * Override of ActivityItemsElement to support all relations except 'contact'.
     *
     */
    class ActivityItemsExcludingContactsElement extends ActivityItemsElement
    {
        protected function renderControlEditable()
        {
            assert('$this->model instanceof Activity');
            $metadata = Activity::getMetadata();
            $activityItemsModelClassNamesData = $metadata['Activity']['activityItemsModelClassNames'];
            foreach ($activityItemsModelClassNamesData as $index => $relationModelClassName)
            {
                if ($relationModelClassName == 'Contact')
                {
                    unset($activityItemsModelClassNamesData[$index]);
                }
            }
            return $this->renderElementsForRelationsByRelationsData($activityItemsModelClassNamesData);
        }
    }
?>