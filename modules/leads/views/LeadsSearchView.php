<?php


    class LeadsSearchView extends fosaSearchView
    {
        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                'derivedAttributeTypes' => array(
                        'LeadStateDropDown',
                    ),
                    'nonPlaceableAttributeNames' => array(
                        'state',
                        'account',
                    ),
                    'panels' => array(
                        array(
                            'title' => 'Basic Search',
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'fullName', 'type' => 'Text'),
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
                                                array('attributeName' => 'department', 'type' => 'Text'), // Not Coding Standard
                                            ),
                                        ),
                                        array(
                                            'elements' => array(
                                                array('attributeName' => 'mobilePhone', 'type' => 'Phone'),
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
            return 'LeadsSearchForm';
        }
    }
?>