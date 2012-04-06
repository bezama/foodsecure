<?php


    /**
     * The base View for the lead's convert view a ctions
     */
    class LeadConvertActionsView extends MetadataView
    {
        protected $controllerId;

        protected $moduleId;

        protected $convertToAccountSetting;

        public function __construct($controllerId, $moduleId, $modelId, $convertToAccountSetting, $userCanCreateAccount)
        {
            assert('is_int($convertToAccountSetting)');
            assert('is_string($controllerId)');
            assert('is_string($moduleId)');
            assert('is_bool($userCanCreateAccount)');
            $this->controllerId            = $controllerId;
            $this->moduleId                = $moduleId;
            $this->modelId                 = $modelId;
            $this->convertToAccountSetting = $convertToAccountSetting;
            $this->userCanCreateAccount    = $userCanCreateAccount;
        }

        /**
         * Renders content for the view.
         * @return A string containing the element's content.
         */
        protected function renderContent()
        {
            Yii::app()->clientScript->registerScript('leadConvertActions', "
                $('.account-select-link').click( function()
                    {
                        $('#AccountConvertToView').hide();
                        $('#LeadConvertAccountSkipView').hide();
                        $('#AccountSelectView').show();
                        $('#account-create-title').hide();
                        $('#account-skip-title').hide();
                        $('#account-select-title').show();
                        return false;
                    }
                );
                $('.account-create-link').click( function()
                    {
                        $('#AccountConvertToView').show();
                        $('#LeadConvertAccountSkipView').hide();
                        $('#AccountSelectView').hide();
                        $('#account-create-title').show();
                        $('#account-skip-title').hide();
                        $('#account-select-title').hide();
                        return false;
                    }
                );
                $('.account-skip-link').click( function()
                    {
                        $('#AccountConvertToView').hide();
                        $('#LeadConvertAccountSkipView').show();
                        $('#AccountSelectView').hide();
                        $('#account-create-title').hide();
                        $('#account-skip-title').show();
                        $('#account-select-title').hide();
                        return false;
                    }
                );
            ");

            $cancelLink = new CancelConvertLinkActionElement($this->controllerId, $this->moduleId, $this->modelId);
            $createLink = CHtml::link(Yii::t('Default', 'Create AccountsModuleSingularLabel',
                            LabelUtil::getTranslationParamsForAllModules()), '#', array('class' => 'account-create-link'));
            $selectLink = CHtml::link(Yii::t('Default', 'Select AccountsModuleSingularLabel',
                            LabelUtil::getTranslationParamsForAllModules()), '#', array('class' => 'account-select-link'));
            $skipLink   = CHtml::link(Yii::t('Default', 'Skip AccountsModuleSingularLabel',
                            LabelUtil::getTranslationParamsForAllModules()), '#', array('class' => 'account-skip-link'));
            $content    = '<div class="view-toolbar">';
            $content   .= $cancelLink->render() . '&#160;';
            $content .= '</div>';
            $content .= '<div id="account-select-title" style="margin-bottom:5px;">';
            if ($this->userCanCreateAccount)
            {
                $content .= $createLink .  '&#160;' . Yii::t('Default', 'or') . '&#160;';
            }
            $content .= '<b>' . Yii::t('Default', 'Select AccountsModuleSingularLabel',
                                    LabelUtil::getTranslationParamsForAllModules()) . '</b>&#160;';
            if ($this->convertToAccountSetting == LeadsModule::CONVERT_ACCOUNT_NOT_REQUIRED)
            {
                $content .= Yii::t('Default', 'or') . '&#160;' . $skipLink;
            }
            $content .= '</div>';
            $content .= '<div id="account-create-title" style="margin-bottom:5px;">';
            $content .= '<b>' . Yii::t('Default', 'Create AccountsModuleSingularLabel',
                                    LabelUtil::getTranslationParamsForAllModules()) . '</b>&#160;';
            $content .= Yii::t('Default', 'or') . '&#160;' . $selectLink . '&#160;';
            if ($this->convertToAccountSetting == LeadsModule::CONVERT_ACCOUNT_NOT_REQUIRED)
            {
                $content .= Yii::t('Default', 'or') . '&#160;' . $skipLink;
            }
            $content .= '</div>';
            if ($this->convertToAccountSetting == LeadsModule::CONVERT_ACCOUNT_NOT_REQUIRED)
            {
                $content .= '<div id="account-skip-title" style="margin-bottom:5px;">';
                if ($this->userCanCreateAccount)
                {
                    $content .= $createLink . '&#160;' . Yii::t('Default', 'or') . '&#160;';
                }
                $content .= $selectLink . '&#160;' . Yii::t('Default', 'or') . '&#160;';
                $content .= '<b>' . Yii::t('Default', 'Skip AccountsModuleSingularLabel',
                                        LabelUtil::getTranslationParamsForAllModules()) . '</b>&#160;';
                $content .= '</div>';
            }
            return $content;
        }
    }
?>