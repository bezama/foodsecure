<?php


    class ContactsForOpportunityRelatedListView extends ContactsRelatedListView
    {
        /**
         * Override the panels and toolbar metadata.
         */
        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata['global']['toolbar']['elements'][] =
                                array('type'                 => 'SelectFromRelatedListAjaxLink',
                                    'portletId'              => 'eval:$this->params["portletId"]',
                                    'relationAttributeName'  => 'eval:$this->getRelationAttributeName()',
                                    'relationModelId'        => 'eval:$this->params["relationModel"]->id',
                                    'relationModuleId'       => 'eval:$this->params["relationModuleId"]',
                                    'uniqueLayoutId'         => 'eval:$this->uniqueLayoutId',
                                //TODO: fix this 'eval' of $this->uniqueLayoutId above so that it can properly work being set/get from DB then getting evaluated
                                //currently it will not work correctly since in the db it would store a static value instead of it still being dynamic
                                    'ajaxOptions' => array(
                                        'onclick' => '$("#modalContainer").dialog("open"); return false;',
                                        'update'  => '#modalContainer',
                                    ),
                                    'htmlOptions' => array( 'id' => 'SelectContactsForOpportunityFromRelatedListLink',
                                                            'live' => false) //This is there are no double bindings
            );
            $metadata['global']['panels'] = array(
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
            );
            return $metadata;
        }

        protected function getRelationAttributeName()
        {
            return 'opportunities';
        }

        public static function getDisplayDescription()
        {
            return Yii::t('Default', 'ContactsModulePluralLabel For OpportunitiesModuleSingularLabel',
                        LabelUtil::getTranslationParamsForAllModules());
        }
    }
?>