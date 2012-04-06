<?php


    class ContactStateAttributeForm extends AttributeForm implements CollectionAttributeFormInterface
    {
        public $contactStatesData;

        public $startingStateOrder;

        public $contactStatesLabels;

        /**
         * Used when changing the value of an existing data item.  Coming in from a post, this array will have the
         * old values that can be used to compare against and update the new values accordingly based on any changes.
         */
        public $contactStatesDataExistingValues;

        public function __construct(Contact $model = null, $attributeName = null)
        {
            assert('$model != null');
            assert('$attributeName != null && is_string($attributeName)');
            parent::__construct($model, $attributeName);
            $this->contactStatesData   = ContactsUtil::getContactStateDataKeyedByOrder();
            $this->contactStatesLabels = ContactsUtil::getContactStateLabelsKeyedByLanguageAndOrder();
            $startingState             = ContactsUtil::getStartingState();
            $this->startingStateOrder  = $startingState->order;
        }

        public function rules()
        {
            return array_merge(parent::rules(), array(
                array('startingStateOrder',   'required'),
                array('contactStatesData',    'safe'),
                array('contactStatesData',    'required', 'message' => 'You must have at least one status.'),
                array('contactStatesData',    'validateContactStatesData'),
                array('contactStatesLabels',  'safe'),
                array('contactStatesDataExistingValues',  'safe'),
            ));
        }

        public function attributeLabels()
        {
            return array_merge(parent::attributeLabels(), array(
                'contactStatesData'      => Yii::t('Default', 'Contact Statuses'),
                'startingStateOrder'     => Yii::t('Default', 'Starting Status'),
                'contactStatesLablsa'    => Yii::t('Default', 'Contact Status Translated Labels'),
            ));
        }

        public static function getAttributeTypeDisplayName()
        {
            return Yii::t('Default', 'Contact Stage');
        }

        public static function getAttributeTypeDisplayDescription()
        {
            return Yii::t('Default', 'The contact stage field');
        }

        public function getAttributeTypeName()
        {
            return 'ContactState';
        }

        /**
         * Override to handle startingStateOrder since the attributePropertyToDesignerFormAdapter does not specifically
         * support this property.
         */
        public function canUpdateAttributeProperty($propertyName)
        {
            if ($propertyName == 'startingStateOrder')
            {
                return true;
            }
            return $this->attributePropertyToDesignerFormAdapter->canUpdateProperty($propertyName);
        }

        /**
         * @see AttributeForm::getModelAttributeAdapterNameForSavingAttributeFormData()
         */
        public static function getModelAttributeAdapterNameForSavingAttributeFormData()
        {
            return 'ContactStateModelAttributesAdapter';
        }

        /**
         * Test if there are two picklist values with the same name.  This is not allowed.
         */
        public function validateContactStatesData($attribute, $params)
        {
            $data = $this->$attribute;
            if (array_diff_key( $data , array_unique( $data )) )
            {
                $this->addError('contactStatesData',
                    Yii::t('Default', 'Each ContactsModuleSingularLowerCaseLabel state must be uniquely named',
                                                        LabelUtil::getTranslationParamsForAllModules()));
            }
            foreach ($data as $order => $name)
            {
                $contactState = new ContactState();
                $contactState->name = $name;
                $contactState->order = $order;
                if (!$contactState->validate())
                {
                    foreach ($contactState->getErrors() as $attributeName => $errors)
                    {
                        if ($attributeName == 'name')
                        {
                            foreach ($errors as $error)
                            {
                            $this->addError('contactStatesData', $error);
                            }
                        }
                    }
                }
            }
            //todo: validate against contactState rules as well. like minimum length = 3
        }

        /**
         * Get how many records in the Contact and Lead models have each ContactState selected.
         * During testing, it is possible a contact or lead exists with a contact state id that no longer exists.
         * In that case, it is ignored from the count.
         */
        public function getCollectionCountData()
        {
            $contactStates      = ContactsUtil::getContactStateDataKeyedById();
            $stateNameCountData = array();
            $idCountData        = GroupedAttributeCountUtil::getCountData('Contact', 'state');
            foreach ($idCountData as $id => $count)
            {
                if (isset($contactStates[$id]))
                {
                    $stateNameCountData[$contactStates[$id]] = $count;
                }
            }
            return $stateNameCountData;
        }

        /**
         * Even though contacts and leads use contact state, for now we are treating this only as one model with
         * one attribute using this.
         */
        public function getModelPluralNameAndAttributeLabelsThatUseCollectionData()
        {
            return array();
        }
    }
?>