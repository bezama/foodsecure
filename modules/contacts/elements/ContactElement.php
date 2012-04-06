<?php


    /**
     * Display the contact selection. This is a
     * combination of a type-ahead input text field
     * and a selection button which renders a modal list view
     * to search on contact.  Also includes a hidden input for the user
     * id.
     */
    class ContactElement extends ModelElement
    {
        protected static $moduleId = 'contacts';

        /**
         * Render a hidden input, a text input with an auto-complete
         * event, and a select button. These three items together
         * form the Contact Editable Element
         * @return The element's content as a string.
         */
        protected function renderControlEditable()
        {
            assert('$this->model->{$this->attribute} instanceof Contact');
            return parent::renderControlEditable();
        }
    }
?>
