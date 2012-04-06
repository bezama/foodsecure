<?php


    /**
     * The view for lead conversion, no account, just shows a complete
     * conversion button.
     */
    class LeadConvertAccountSkipView extends MetadataView
    {
        public function __construct()
        {
        }

        /**
         * Renders content for a view.
         * @return A string containing the element's content.
         */
        protected function renderContent()
        {
            $content = '<div class="wide form">';
            $clipWidget = new ClipWidget();
            list($form, $formStart) = $clipWidget->renderBeginWidget(
                                                                'NoRequiredsActiveForm',
                                                                array('id' => 'account-skip-form', 'enableAjaxValidation' => false)
                                                            );
            $content .= $formStart;
            $content .= $this->renderFormLayout($form);
            $formEnd  = $clipWidget->renderEndWidget();
            $content .= $formEnd;
            $content .= '</div>';
            return $content;
        }

        protected function renderFormLayout($form = null)
        {
            $content  = '<table>';
            $content .= '<colgroup>';
            $content .= '<col style="width:100%" />';
            $content .= '</colgroup>';
            $content .= '<tbody>';
            $content .= '<tr>';
            $content .= '<th>' . Yii::t('Default', 'Complete LeadsModuleSingularLowerCaseLabel conversion without ' .
                                                   'selecting or creating an AccountsModuleSingularLowerCaseLabel.',
                                                   LabelUtil::getTranslationParamsForAllModules()) . '</th>';
            $content .= '</tr>';
            $content .= '</tbody>';
            $content .= '</table>';
            $content .= CHtml::submitButton(Yii::t('Default', 'Complete Conversion'), array('name' => 'AccountSkip'));
            return $content;
        }
    }
?>
