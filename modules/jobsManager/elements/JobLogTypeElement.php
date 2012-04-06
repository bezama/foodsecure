<?php


    /**
     * Displays the job type
     */
    class JobLogTypeElement extends Element
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
            assert('$this->attribute == "type"');
            $jobType = $this->model->{$this->attribute};
            $jobClassName = $jobType . 'Job';
            return $jobClassName::getDisplayName();
        }
    }
?>
