<?php


    /**
     * Display a drop down of contact states.
     * This element is used by the designer for managing
     * the contact state attribute.
     */
    class AllContactStatesDropDownElement extends StaticDropDownFormElement implements DerivedElementInterface
    {
        /**
         * Override because in the user interface, the dynamic way in which this drop down is changed based on changes
         * in the user interface relies on the id of the drop down being a structured a certain way.
         * The example usage is in the designer tool -> Contacts -> fields -> status -> edit.
         * @see DropDownElement::getIdForSelectInput()
         */
        public function getIdForSelectInput()
        {
            return $this->getEditableInputId($this->attribute);
        }

        protected function renderControlNonEditable()
        {
            $relatedAttributeName = $this->getRelatedAttributeName();
            assert('$relatedAttributeName != null');
            return parent::renderControlNonEditable();
        }

        protected function getRelatedAttributeName()
        {
            if (isset($this->params['relatedAttributeName']))
            {
                return $this->params['relatedAttributeName'];
            }
            return null;
        }

        protected function getDropDownArray()
        {
            $relatedAttributeName = $this->getRelatedAttributeName();
            assert('$relatedAttributeName != null');
            $dropDownArray = $this->model->{$relatedAttributeName};
            if ($dropDownArray == null)
            {
                return array();
            }
            return $dropDownArray;
        }

        /**
         * Get the attributeNames of attributes used in
         * the derived element.
         * @return array of model attributeNames used.
         */
        public static function getModelAttributeNames()
        {
            return array(
                'state',
            );
        }
    }
?>
