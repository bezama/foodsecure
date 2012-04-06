<?php


    /**
     * Class used for the dashboard, selectable by users to display a list of their meetings or filtered any way.
     */
    class MeetingsMyListView extends SecuredMyListView
    {
        protected function getSortAttributeForDataProvider()
        {
            return 'startDateTime';
        }

        public static function getDefaultMetadata()
        {
            $metadata = array(
                'perUser' => array(
                    'title' => "eval:Yii::t('Default', 'My Upcoming MeetingsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules())",
                    'searchAttributes' => array('ownedItemsOnly' => true,
                                                'startDateTime__DateTime' =>
                                                    array('type' => MixedDateTypesSearchFormAttributeMappingRules::TYPE_NEXT_7_DAYS)),
                ),
                'global' => array(
                    'nonPlaceableAttributeNames' => array(
                        'latestDateTime',
                    ),
                    'panels' => array(
                        array(
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'name', 'type' => 'Text', 'isLink' => true),
                                            ),
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'startDateTime', 'type' => 'DateTime'),
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

        public static function getModuleClassName()
        {
            return 'MeetingsModule';
        }

        public static function getDisplayDescription()
        {
            return Yii::t('Default', 'My Upcoming MeetingsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules());
        }

        protected function getSearchModel()
        {
            $modelClassName = $this->modelClassName;
            return new MeetingsSearchForm(new $modelClassName(false));
        }

        protected static function getConfigViewClassName()
        {
            return 'MeetingsMyListConfigView';
        }
    }
?>