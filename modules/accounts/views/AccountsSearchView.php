<?php


    class AccountsSearchView extends fosaSearchView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'panels' => array(
                        array(
                            'title' => 'Basic Search',
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'name', 'type' => 'Text'),
                                            ),
                                        ),
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'officePhone', 'type' => 'Phone'),
                                            ),
                                        ),
                                    )
                                ),
                            ),
                        ),
                        array(
                            'title' => 'Advanced Search',
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'industry', 'type' => 'DropDownAsMultiSelect', 'addBlank' => true),
                                            ),
                                        ),
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'type', 'type' => 'DropDownAsMultiSelect', 'addBlank' => true),
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

        public static function getModelForMetadataClassName()
        {
            return 'AccountsSearchForm';
        }
    }
?>