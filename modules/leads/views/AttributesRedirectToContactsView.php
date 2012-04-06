<?php


    /**
     * Helper tool for designer.  When a user select the fields option in the Leads module, it will provide a message
     * explaining that leads attributes are the same as contacts and therefore controlled by the contacts attribute list.
     */
    class AttributesRedirectToContactsView extends View
    {
        protected $controllerId;

        protected $moduleId;

        public function __construct($controllerId, $moduleId)
        {
            $this->controllerId           = $controllerId;
            $this->moduleId               = $moduleId;
        }

        protected function renderContent()
        {
            $content  = '<div class="horizontal-line">';
            $content .= Yii::t('Default', 'LeadsModulePluralLabel and ContactsModulePluralLabel are the same records, ' .
                                          'just in a different status. To create a LeadsModuleSingularLowerCaseLabel ' .
                                          'field, create a ContactsModuleSingularLowerCaseLabel field, and then it ' .
                                          'will be placeable in the LeadsModulePluralLowerCaseLabel layouts.',
                                          LabelUtil::getTranslationParamsForAllModules());
            $content .= '</div>' . "\n";
            return $content;
        }

        public function isUniqueToAPage()
        {
            return true;
        }
    }
?>