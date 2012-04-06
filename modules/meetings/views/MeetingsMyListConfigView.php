<?php


    /**
     * View for showing the configuration parameters for the @see MeetingsMyListView.
     */
    class MeetingsMyListConfigView extends MyListConfigView
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
                    'nonPlaceableAttributeNames' => array(
                        'latestDateTime',
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
                                                array('attributeName' => 'startDateTime__DateTime', 'type' => 'MixedDateTypesForSearch'),
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
            return Yii::t('Default', 'My Upcoming MeetingsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules());
        }

        public static function getModelForMetadataClassName()
        {
            return 'MeetingsSearchForm';
        }
    }
?>