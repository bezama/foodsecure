<?php


    /**
     * View for showing the configuration parameters for the @see AccountsMyListView.
     */
    class AccountsMyListConfigView extends MyListConfigView
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
                    'panelsDisplayType' => FormLayout::PANELS_DISPLAY_TYPE_ALL,
                    'panels' => array(
                        array(
                            'title' => 'List Filters',
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'type', 'type' => 'DropDownAsMultiSelect', 'addBlank' => true),
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
            return Yii::t('Default', 'My AccountsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules());
        }

        public static function getModelForMetadataClassName()
        {
            return 'AccountsSearchForm';
        }
    }
?>