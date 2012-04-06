<?php


    /**
     * View for showing the configuration parameters for the @see ContactsMyListView.
     */
    class ContactsMyListConfigView extends MyListConfigView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'toolbar' => array(
                        'elements' => array(
                            array('type' => 'SaveButton'),
                        ),
                    ),
                    'derivedAttributeTypes' => array(
                        'ContactStateDropDown',
                    ),
                    'nonPlaceableAttributeNames' => array(
                        'state',
                    ),
                    'panelsDisplayType' => FormLayout::PANELS_DISPLAY_TYPE_ALL,
                    'panels' => array(
                        array(
                            'title' => 'List Filters',
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'null', 'type' => 'ContactStateDropDown', 'addBlank' => true),
                                            ),
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'ownedItemsOnly', 'type' => 'CheckBox'),
                                            ),
                                        ),
                                    )
                                ),
                            ),
                        ),
                    ),
                ),
            );
            return $metadata;
        }

        public static function getDisplayDescription()
        {
            return Yii::t('Default', 'My ContactsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules());
        }

        public static function getModelForMetadataClassName()
        {
            return 'ContactsSearchForm';
        }
    }
?>