<?php


    class ContactStateDropDownElement extends DropDownElement implements DerivedElementInterface
    {
        /**
         * Override to utilize 'id' as the attribute not 'value'
         */
        protected function renderControlEditable()
        {
            return $this->form->dropDownList(
                $this->model->{$this->attribute},
                'id',
                $this->getDropDownArray(),
                $this->getEditableHtmlOptions()
            );
        }

        /**
         * Renders the noneditable dropdown content.
         * Takes the model attribute value and converts it into the proper display value
         * based on the corresponding dropdown display label.
         * @return A string containing the element's content.
         */
        protected function renderControlNonEditable()
        {
            $label = ContactsUtil::resolveStateLabelByLanguage($this->model->{$this->attribute}, Yii::app()->language);
            return Yii::app()->format->text($label);
        }

        /**
         * Override so we can force attribute to be set at 'state' since this
         * is the correct attributeName for anything using this derived element
         */
        public function __construct($model, $attribute, $form = null, array $params = array())
        {
            assert('$attribute == "null"');
            parent::__construct($model, $attribute, $form, $params);
            $this->attribute = 'state';
        }

        protected function getDropDownArray()
        {
            return ContactsUtil::getContactStateDataFromStartingStateKeyedByIdAndLabelByLanguage(Yii::app()->language);
        }

        public static function getDisplayName()
        {
            return Yii::t('Default', 'Status');
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

        public function getIdForSelectInput()
        {
            return $this->getEditableInputId($this->attribute, 'id');
        }

        protected function getNameForSelectInput()
        {
            return $this->getEditableInputName($this->attribute, 'id');
        }
    }
?>