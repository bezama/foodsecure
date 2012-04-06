<?php


    /**
     * Display radio buttons for the convert to account
     * setting in the Leads module.
     */
    class LeadsConvertToAccountRadioElement extends Element
    {
        /**
         * Renders the setting as a radio list.
         * @return A string containing the element's content.
         */
        protected function renderControlEditable()
        {
            $content = $this->form->radioButtonList(
                $this->model,
                $this->attribute,
                $this->getArray(),
                $this->getEditableHtmlOptions()
            );
            return $content;
        }

        protected function renderControlNonEditable()
        {
            throw new NotImplementedException();
        }

        /**
         * Override to ensure label is pointing to the right input id
         * @return A string containing the element's label
         */
        protected function renderLabel()
        {
            if ($this->form === null)
            {
                throw new NotImplementedException();
            }
            $for = CHtml::ID_PREFIX . $this->getEditableInputId();
            return $this->form->labelEx($this->model, $this->attribute, array('for' => $for));
        }

        public function getEditableHtmlOptions()
        {
            $htmlOptions = array(
                'name' => $this->getEditableInputName(),
                'id'   => $this->getEditableInputId(),
            );
            $htmlOptions['template'] =  '{label} {input}';
            return $htmlOptions;
        }

        protected function getArray()
        {
            return array(
                LeadsModule::CONVERT_NO_ACCOUNT           =>
                Yii::t('Default', 'Do not show AccountsModuleSingularLabel', LabelUtil::getTranslationParamsForAllModules()),
                LeadsModule::CONVERT_ACCOUNT_NOT_REQUIRED =>
                Yii::t('Default', 'AccountsModuleSingularLabel Optional', LabelUtil::getTranslationParamsForAllModules()),
                LeadsModule::CONVERT_ACCOUNT_REQUIRED     =>
                Yii::t('Default', 'AccountsModuleSingularLabel Required', LabelUtil::getTranslationParamsForAllModules()));
        }
    }
?>