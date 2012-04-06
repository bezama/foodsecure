<?php


    /**
     * Display the contact selection including leads. No state filter will be applied unless the current user
     * has a security restriction.  This is a
     * combination of a type-ahead input text field
     * and a selection button which renders a modal list view
     * to search on contact.  Also includes a hidden input for the user
     * id.
     */
    class AllStatesContactElement extends ContactElement
    {
        protected static $moduleId = 'contacts';

        protected static $autoCompleteActionId = 'autoCompleteAllContacts';

        protected static $modalActionId = 'modalListAllContacts';

        protected function renderLabel()
        {
            $label = Yii::t('Default', 'ContactsModuleSingularLabel or LeadsModuleSingularLabel',
                                                LabelUtil::getTranslationParamsForAllModules());
            if ($this->form === null)
            {
                return $this->getFormattedAttributeLabel();
            }
            $id = $this->getIdForHiddenField();
            return $this->form->labelEx($this->model, $this->attribute, array('for' => $id, 'label' => $label));
        }

        protected function getAutoCompleteControllerId()
        {
            return 'variableContactState';
        }

        protected function getSelectLinkControllerId()
        {
            return 'variableContactState';
        }
    }
?>
