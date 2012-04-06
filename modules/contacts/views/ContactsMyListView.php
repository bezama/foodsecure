<?php


    /**
     * Class used for the dashboard, selectable by users to display a list of their contacts or filtered any way.
     */
    class ContactsMyListView extends SecuredMyListView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'perUser' => array(
                    'title' => "eval:Yii::t('Default', 'My ContactsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules())",
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
                                                array('attributeName' => 'account', 'type' => 'Account', 'isLink' => true),
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
            return 'ContactsModule';
        }

        public static function getDisplayDescription()
        {
            return Yii::t('Default', 'My ContactsModulePluralLabel', LabelUtil::getTranslationParamsForAllModules());
        }

        protected function getSearchModel()
        {
            $modelClassName = $this->modelClassName;
            return new ContactsSearchForm(new $modelClassName(false));
        }

        protected static function getConfigViewClassName()
        {
            return 'ContactsMyListConfigView';
        }
    }
?>