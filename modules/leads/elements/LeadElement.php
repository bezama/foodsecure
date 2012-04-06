<?php


    /**
     * Display the contact selection filtered by lead specific states. This is a
     * combination of a type-ahead input text field
     * and a selection button which renders a modal list view
     * to search on contact.  Also includes a hidden input for the user
     * id.
     */
    class LeadElement extends ContactElement
    {
        protected static $moduleId = 'leads';

        protected function renderLabel()
        {
            $label = LeadsModule::getModuleLabelByTypeAndLanguage('Singular');
            if ($this->form === null)
            {
                return $this->getFormattedAttributeLabel();
            }
            $id = $this->getIdForHiddenField();
            return $this->form->labelEx($this->model, $this->attribute, array('for' => $id, 'label' => $label));
        }
    }
?>
