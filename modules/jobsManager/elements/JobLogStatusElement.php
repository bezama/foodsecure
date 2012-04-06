<?php


    /**
     * Displays the job status
     */
    class JobLogStatusElement extends Element
    {
        protected function renderControlEditable()
        {
            throw new NotImplementedException();
        }

        /**
         * Renders the attribute from the model.
         * @return The element's content.
         */
        protected function renderControlNonEditable()
        {
            assert('$this->attribute == "status"');
            if ($this->model->{$this->attribute} == JobLog::STATUS_COMPLETE_WITH_ERROR)
            {
                return Yii::t('Default', 'Completed with Errors');
            }
            elseif ($jobLog->status == JobLog::STATUS_COMPLETE_WITHOUT_ERROR)
            {
                return Yii::t('Default', 'Completed');
            }
            else
            {
                throw new NotSupportedException();
            }
        }
    }
?>