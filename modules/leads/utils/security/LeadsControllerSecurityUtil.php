<?php


    /**
     * Helper class to assist with security checks in the leads module specific controllers
     */
    class LeadsControllerSecurityUtil extends ControllerSecurityUtil
    {
        /**
         * There are several scenarios that can occur where a user has the right to convert, but is missing other
         * rights in order to properly utilize the convert mechanism.  This method checks for those conditions, and
         * if present, will alert the user that there is a misconfiguration and they should contact their administrator.
         * Scenario #1 - User does not have access to contacts
         * Scenario #2 - User cannot access accounts and an account is required for conversion
         */
        public static function resolveCanUserProperlyConvertLead($userCanAccessContacts, $userCanAccessAccounts,
                                                                 $convertToAccountSetting)
        {
            assert('is_bool($userCanAccessContacts)');
            assert('is_bool($userCanAccessAccounts)');
            assert('is_int($convertToAccountSetting)');
            $userCanConvertProperly = true;
            //Scenario #1 - User does not have access to contacts
            if (!$userCanAccessContacts)
            {
                $scenarioSpecificContent = // Not Coding Standard
                Yii::t('Default', 'Conversion requires access to the ContactsModulePluralLowerCaseLabel' .
                                  ' module which you do not have. Please contact your administrator.',
                       LabelUtil::getTranslationParamsForAllModules());
                $userCanConvertProperly  = false;
            }
            //Scenario #2 - User cannot access accounts and an account is required for conversion
            elseif ( !$userCanAccessAccounts && $convertToAccountSetting == LeadsModule::CONVERT_ACCOUNT_REQUIRED)
            {
                $scenarioSpecificContent = // Not Coding Standard
                Yii::t('Default', 'Conversion is set to require an AccountsModuleSingularLowerCaseLabel.  Currently' .
                                  ' you do not have access to the AccountsModulePluralLowerCaseLabel module.' .
                                  ' Please contact your administrator.',
                       LabelUtil::getTranslationParamsForAllModules());
                $userCanConvertProperly  = false;
            }
            if ($userCanConvertProperly)
            {
                return;
            }
            static::processAccessFailure(false, $scenarioSpecificContent);
            Yii::app()->end(0, false);
        }
    }
?>