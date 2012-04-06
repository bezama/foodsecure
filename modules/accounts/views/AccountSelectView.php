<?php


    /**
     * The view for lead conversion, shows selecting an account.
     */
    class AccountSelectView extends MetadataView
    {
        /**
         * Construct the view to display an input to select an account
         */
        public function __construct($model)
        {
            assert('$model != null');
            assert('$model instanceof AccountSelectForm');
            $this->model    = $model;
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
                                                                'fosaActiveForm',
                                                                array('id' => 'select-account-form', 'enableAjaxValidation' => false)
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
            $content = '<table>';
            $content .= TableUtil::getColGroupContent(1);
            $content .= '<tbody>';
            $content .= '<tr>';
            $element  = new AccountNameIdElement($this->model, 'null', $form);
            $content .= $element->render();
            $content .= '</tr>';
            $content .= '</tbody>';
            $content .= '</table>';
            $content .= CHtml::submitButton(Yii::t('Default', 'Complete Conversion'));
            return $content;
        }
    }
?>
