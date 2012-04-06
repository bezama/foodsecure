<?php


    class LeadConvertView extends GridView
    {
        public function __construct(
                $controllerId,
                $moduleId,
                $modelId,
                $title,
                $selectAccountform,
                $account,
                $convertToAccountSetting,
                $userCanCreateAccount
            )
        {
            assert('$convertToAccountSetting != LeadsModule::CONVERT_NO_ACCOUNT');
            assert('is_bool($userCanCreateAccount)');

            //if has errors, then show by default
            if ($selectAccountform->hasErrors())
            {
                Yii::app()->clientScript->registerScript('leadConvert', "
                    $(document).ready(function()
                        {
                            $('#AccountConvertToView').hide();
                            $('#LeadConvertAccountSkipView').hide();
                            $('#account-skip-title').hide();
                            $('#account-create-title').hide();
                        }
                    );
                ");
            }
            else
            {
                if ($userCanCreateAccount)
                {
                    Yii::app()->clientScript->registerScript('leadConvert', "
                        $(document).ready(function()
                            {
                                $('#AccountSelectView').hide();
                                $('#LeadConvertAccountSkipView').hide();
                                $('#account-skip-title').hide();
                                $('#account-select-title').hide();
                            }
                        );
                    ");
                }
                else
                {
                    Yii::app()->clientScript->registerScript('leadConvert', "
                        $(document).ready(function()
                            {
                                $('#account-create-title').hide();
                                $('#AccountConvertToView').hide();
                                $('#LeadConvertAccountSkipView').hide();
                                $('#account-skip-title').hide();
                            }
                        );
                    ");
                }
            }
            if ($convertToAccountSetting == LeadsModule::CONVERT_ACCOUNT_NOT_REQUIRED)
            {
                $gridSize = 5;
            }
            else
            {
                $gridSize = 4;
            }
            parent::__construct($gridSize, 1);
            $this->setView(new TitleBarView(Yii::t('Default', 'LeadsModuleSingularLabel Conversion',
                                                LabelUtil::getTranslationParamsForAllModules()), $title), 0, 0);
            $this->setView(new LeadConvertActionsView($controllerId, $moduleId, $modelId, $convertToAccountSetting,
                                                      $userCanCreateAccount), 1, 0);
            $this->setView(new AccountSelectView($selectAccountform), 2, 0);
            $this->setView(new AccountConvertToView($controllerId, $moduleId, $account), 3, 0);

            if ($convertToAccountSetting == LeadsModule::CONVERT_ACCOUNT_NOT_REQUIRED)
            {
                $this->setView(new LeadConvertAccountSkipView(), 4, 0);
            }
        }

        public function isUniqueToAPage()
        {
            return true;
        }
    }
?>