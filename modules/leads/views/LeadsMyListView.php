<?php


    /**
     * Class used for the dashboard, selectable by users to display a list of their leads or filtered any way.
     */
    class LeadsMyListView extends SecuredMyListView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'perUser' => array(
                    'title' => "eval:Yii::t('Default', 'My LeadsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules())",
                    'searchAttributes' => array('ownedItemsOnly' => true),
                ),
                'global' => array(
                    'derivedAttributeTypes' => array(
                        'FullName',
                    ),
                    'panels' => array(
                        array(
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'null', 'type' => 'FullName', 'isLink' => true),
                                            ),
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'companyName', 'type' => 'Text'),
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
            return 'LeadsModule';
        }

        public static function getDisplayDescription()
        {
            return Yii::t('Default', 'My LeadsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules());
        }

        protected function getSearchModel()
        {
            $modelClassName = $this->modelClassName;
            return new LeadsSearchForm(new $modelClassName(false));
        }

        protected static function getConfigViewClassName()
        {
            return 'LeadsMyListConfigView';
        }
    }
?>