<?php


    class ContactsSearchForm extends OwnedSearchForm
    {
        public $anyCity;
        public $anyStreet;
        public $anyState;
        public $anyPostalCode;
        public $anyCountry;
        public $anyEmail;
        public $anyInvalidEmail;
        public $anyOptOutEmail;
        public $fullName;

        public function rules()
        {
            return array_merge(parent::rules(), array(
                array('anyCity', 'safe'),
                array('anyStreet', 'safe'),
                array('anyState', 'safe'),
                array('anyPostalCode', 'safe'),
                array('anyCountry', 'safe'),
                array('anyEmail', 'safe'),
                array('anyInvalidEmail', 'boolean'),
                array('anyOptOutEmail', 'boolean'),
                array('fullName', 'safe'),
            ));
        }

        public function attributeLabels()
        {
            return array_merge(parent::attributeLabels(), array(
                'anyCity'            => Yii::t('Default', 'Any City'),
                'anyStreet'          => Yii::t('Default', 'Any Street'),
                'anyState'           => Yii::t('Default', 'Any State'),
                'anyPostalCode'      => Yii::t('Default', 'Any Postal Code'),
                'anyCountry'         => Yii::t('Default', 'Any Country'),
                'anyEmail'           => Yii::t('Default', 'Any Email Address'),
                'anyInvalidEmail'    => Yii::t('Default', 'Any Invalid Email'),
                'anyOptOutEmail'     => Yii::t('Default', 'Any Opted Out Email'),
                'fullName'           => Yii::t('Default', 'Name'),
            ));
        }

        public function getAttributesMappedToRealAttributesMetadata()
        {
            return array_merge(parent::getAttributesMappedToRealAttributesMetadata(), array(
                'anyCity' => array(
                    array('primaryAddress',  'city'),
                    array('secondaryAddress', 'city'),
                ),
                'anyStreet' => array(
                    array('primaryAddress',  'street1'),
                    array('secondaryAddress', 'street1'),
                ),
                'anyState' => array(
                    array('primaryAddress',  'state'),
                    array('secondaryAddress', 'state'),
                ),
                'anyPostalCode' => array(
                    array('primaryAddress',  'postalCode'),
                    array('secondaryAddress', 'postalCode'),
                ),
                'anyCountry' => array(
                    array('primaryAddress',  'country'),
                    array('secondaryAddress', 'country'),
                ),
                'anyEmail' => array(
                    array('primaryEmail',   'emailAddress'),
                    array('secondaryEmail', 'emailAddress'),
                ),
                'anyInvalidEmail' => array(
                    array('primaryEmail',   'isInvalid'),
                    array('secondaryEmail', 'isInvalid'),
                ),
                'anyOptOutEmail' => array(
                    array('primaryEmail',   'optOut'),
                    array('secondaryEmail', 'optOut'),
                ),
                'fullName' => array(
                    array('firstName'),
                    array('lastName'),
                    array('concatedAttributeNames' => array('firstName', 'lastName'))
                ),
            ));
        }
    }
?>