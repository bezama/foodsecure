<?php


    /**
     * ApiControllerSecurityUtil::renderAccessFailureView shouldn't generate view,
     * but rather throw exception
     */
    class ApiControllerSecurityUtil extends ControllerSecurityUtil
    {
        /**
         * Generate security exception, in case when user doesn't have permissions for requested action.
         * @param boolean $fromAjax
         * @param $nonAjaxFailureMessageContent
         * @throws SecurityException
         */
        protected static function renderAccessFailureContent($fromAjax = false, $nonAjaxFailureMessageContent = null)
        {
            $message = Yii::t('Default', 'You do not have permissions for this action.');
            throw new SecurityException($message);
        }
    }
?>