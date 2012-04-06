<?php


    class ContactStateAttributeEditView extends AttributeEditView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'toolbar' => array(
                        'elements' => array(
                            array('type' => 'SaveButton'),
                        ),
                    ),
                    'panelsDisplayType' => FormLayout::PANELS_DISPLAY_TYPE_ALL,
                    'panels' => array(
                        array(
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'null', 'type' => 'AttributeType'),
                                            ),
                                        ),
                                    ),
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'attributeLabels', 'type' => 'AttributeLabel'),
                                            ),
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'attributeName', 'type' => 'Text'),
                                            ),
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'startingStateOrder', 'type' => 'AllContactStatesDropDown',
                                                    'relatedAttributeName' => 'contactStatesData'
                                                ),
                                            ),
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'isRequired', 'type' => 'CheckBox'),
                                            ),
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'isAudited', 'type' => 'CheckBox'),
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

        protected function renderAfterFormLayout($form)
        {
            $titleBar = new TitleBarView (Yii::t('Default', 'Contact Statuses'));
            $content  = $titleBar->render();
            $content .= '<div class="horizontal-line"></div>' . "\n";
            $content .= '<div>' . "\n";
            $element  = new EditableDropDownCollectionElement($this->model, 'contactStatesData', $form,
                                array('specificValueFromDropDownAttributeName' => 'startingStateOrder',
                                      'baseLanguage'           => Yii::app()->languageHelper->getBaseLanguage(),
                                      'activeLanguagesData'    => Yii::app()->languageHelper->getActiveLanguagesData(),
                                      'labelsAttributeName'    => 'contactStatesLabels'));
            $content .= $element->render();
            $content .= '</div>' . "\n";
            return $content;
        }
    }
?>
