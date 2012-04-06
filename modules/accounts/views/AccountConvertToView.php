<?php


    class AccountConvertToView extends EditView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'panelsDisplayType' => FormLayout::PANELS_DISPLAY_TYPE_ALL,
                    'panels' => array(
                        array(
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'name', 'type' => 'Text'),
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

        /**
         * Override to remove any remnants of the BiewToolBar.
         */
        protected function renderViewToolBar($renderInForm = true)
        {
            return;
        }

        protected function renderAfterFormLayout($form)
        {
            return CHtml::submitButton(Yii::t('Default', 'Complete Conversion'));
        }
    }
?>