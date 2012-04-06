<?php


    /**
     * Helper functions to assist with testing designer walkthroughs specifically for account layouts.
     */
    class AccountsDesignerWalkthroughHelperUtil
    {
        public static function getAccountEditAndDetailsViewLayoutWithAllCustomFieldsPlaced()
        {
            return array(
                    'panels' => array(
                        array(
                            'title' => 'Panel Title',
                            'panelDetailViewOnly' => 1,
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'name',
                                        ),
                                        array(
                                            'element' => 'officePhone',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'owner',
                                        ),
                                        array(
                                            'element' => 'Null', // Not Coding Standard
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'industry',
                                        ),
                                        array(
                                            'element' => 'officeFax',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'employees',
                                        ),
                                        array(
                                            'element' => 'annualRevenue',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'type',
                                        ),
                                        array(
                                            'element' => 'website',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'primaryEmail',
                                        ),
                                        array(
                                            'element' => 'secondaryEmail',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'billingAddress',
                                        ),
                                        array(
                                            'element' => 'shippingAddress',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'detailViewOnly' => true,
                                            'element' => 'DateTimeCreatedUser',
                                        ),
                                        array(
                                            'detailViewOnly' => true,
                                            'element' => 'DateTimeModifiedUser',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'description',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'checkbox',
                                        ),
                                        array(
                                            'element' => 'currency',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'date',
                                        ),
                                        array(
                                            'element' => 'datetime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'decimal',
                                        ),
                                        array(
                                            'element' => 'picklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'integer',
                                        ),
                                        array(
                                            'element' => 'multiselect',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'tagcloud',
                                        ),
                                        array(
                                            'element' => 'calculatednumber',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'dropdowndependency',
                                        ),
                                        array(
                                            'element' => 'dropdowndependency2',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'phone',
                                        ),
                                        array(
                                            'element' => 'radio',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'text',
                                        ),
                                        array(
                                            'element' => 'textarea',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'url',
                                        ),
                                        array(
                                            'element' => 'Null', // Not Coding Standard
                                        ),
                                    )
                                ),
                            ),
                        ),
                    ),
            );
        }

        public static function getAccountsSearchViewLayoutWithAllCustomFieldsPlaced()
        {
            return array(
                    'panels' => array(
                        array(
                            'title' => 'Basic Search',
                            'panelDetailViewOnly' => 1,
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'checkbox',
                                        ),
                                        array(
                                            'element' => 'currency',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'date__Date',
                                        ),
                                        array(
                                            'element' => 'datetime__DateTime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'decimal',
                                        ),
                                        array(
                                            'element' => 'picklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'integer',
                                        ),
                                        array(
                                            'element' => 'multiselect',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'tagcloud',
                                        ),
                                        array(
                                            'element' => 'countrypicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'statepicklist',
                                        ),
                                        array(
                                            'element' => 'citypicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'phone',
                                        ),
                                        array(
                                            'element' => 'radio',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'text',
                                        ),
                                        array(
                                            'element' => 'textarea',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'url',
                                        ),
                                        array(
                                            'element' => 'Null', // Not Coding Standard
                                        ),
                                    )
                                ),
                            ),
                        ),
                        array(
                            'title' => 'Advanced Search',
                            'panelDetailViewOnly' => 1,
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'name',
                                        ),
                                        array(
                                            'element' => 'officePhone',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'owner',
                                        ),
                                        array(
                                            'element' => 'Null', // Not Coding Standard
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'industry',
                                        ),
                                        array(
                                            'element' => 'officeFax',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'employees',
                                        ),
                                        array(
                                            'element' => 'annualRevenue',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'type',
                                        ),
                                        array(
                                            'element' => 'website',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'createdDateTime__DateTime',
                                        ),
                                        array(
                                            'element' => 'modifiedDateTime__DateTime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'createdByUser',
                                        ),
                                        array(
                                            'element' => 'modifiedByUser',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'anyCity',
                                        ),
                                        array(
                                            'element' => 'anyStreet',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'anyState',
                                        ),
                                        array(
                                            'element' => 'anyPostalCode',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'anyCountry',
                                        ),
                                        array(
                                            'element' => 'anyEmail',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'anyInvalidEmail',
                                        ),
                                        array(
                                            'element' => 'anyOptOutEmail',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'description',
                                        ),
                                    )
                                ),
                            ),
                        ),
                    ),
            );
        }

        /**
         * Can be use for listView or relatedListView.
         */
        public static function getAccountsListViewLayoutWithAllStandardAndCustomFieldsPlaced()
        {
            return array(
                    'panels' => array(
                        array(
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'name',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'owner',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'officePhone',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'officeFax',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'employees',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'annualRevenue',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'website',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'industry',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'type',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'description',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'createdDateTime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'modifiedDateTime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'createdByUser',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'modifiedByUser',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'billingAddress',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'shippingAddress',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'primaryEmail',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'secondaryEmail',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'checkbox',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'currency',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'date',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'datetime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'decimal',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'picklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'integer',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'multiselect',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'tagcloud',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'calculatednumber',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'countrypicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'statepicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'citypicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'phone',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'radio',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'text',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'textarea',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'url',
                                        ),
                                    )
                                ),
                            ),
                        ),
                    ),
            );
        }

        public static function getAccountsMassEditViewLayoutWithAllStandardAndCustomFieldsPlaced()
        {
            return array(
                    'panels' => array(
                        array(
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'name',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'owner',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'officePhone',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'officeFax',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'employees',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'annualRevenue',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'website',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'industry',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'type',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'checkbox',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'currency',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'date',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'datetime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'decimal',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'picklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'integer',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'multiselect',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'tagcloud',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'countrypicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'statepicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'citypicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'phone',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'radio',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'text',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'url',
                                        ),
                                    )
                                ),
                            ),
                        ),
                    ),
            );
        }

        /**
         * This function returns the necessary get parameters for the account search form
         * based on the account edited data.
         */
        public static function fetchAccountsSearchFormGetData()
        {
            return array(
                            'name'               => 'myEditAccount',
                            'officePhone'        => '259-734-2169',
                            'type'               => array('value'  =>  'Customer'),
                            'officeFax'          => '299-825-7863',
                            'employees'          => '630',
                            'website'            => 'http://www.UnnamedEdit.com',
                            'annualRevenue'      => '472000000',
                            'anyCity'            => 'San Jose',
                            'anyState'           => 'CA',
                            'anyStreet'          => '26378 South Arlington Ave',
                            'anyPostalCode'      => '95131',
                            'anyCountry'         => 'USA',
                            'anyEmail'           => 'info@myEditAccount.com',
                            'anyOptOutEmail'     => array('value' => null),
                            'anyInvalidEmail'    => array('value' => ''),
                            'ownedItemsOnly'     => '1',
                            'industry'           => array('value' => 'Energy'),
                            'decimal'            => '12',
                            'integer'            => '11',
                            'phone'              => '259-784-2069',
                            'text'               => 'This is a test Edit Text',
                            'textarea'           => 'This is a test Edit TextArea',
                            'url'                => 'http://wwww.abc-edit.com',
                            'checkbox'           => array('value'  => '0'),
                            'currency'           => array('value'  => 40),
                            'picklist'           => array('value'  => 'b'),
                            'multiselect'        => array('values' => array('gg', 'hh')),
                            'tagcloud'           => array('values' => array('reading', 'surfing')),
                            'countrypicklist'    => array('value'  => 'aaaa'),
                            'statepicklist'      => array('value'  => 'aaa1'),
                            'citypicklist'       => array('value'  => 'ab1'),
                            'radio'              => array('value'  => 'e'),
                            'date__Date'         => array('type'   => 'Today'),
                            'datetime__DateTime' => array('type'   => 'Today'));
        }

        /**
         * This function returns the necessary get parameters for the account search form
         * based on the account edited data but sets the Multi-select to null value.
         */
        public static function fetchAccountsSearchFormGetDataWithMultiSelectValueSetToNull()
        {
            return array(
                            'name'               => 'myEditAccount',
                            'officePhone'        => '259-734-2169',
                            'type'               => array('value'  =>  'Customer'),
                            'officeFax'          => '299-825-7863',
                            'employees'          => '630',
                            'website'            => 'http://www.UnnamedEdit.com',
                            'annualRevenue'      => '472000000',
                            'anyCity'            => 'San Jose',
                            'anyState'           => 'CA',
                            'anyStreet'          => '26378 South Arlington Ave',
                            'anyPostalCode'      => '95131',
                            'anyCountry'         => 'USA',
                            'anyEmail'           => 'info@myEditAccount.com',
                            'anyOptOutEmail'     => array('value' => null),
                            'anyInvalidEmail'    => array('value' => ''),
                            'ownedItemsOnly'     => '1',
                            'industry'           => array('value' => 'Energy'),
                            'decimal'            => '12',
                            'integer'            => '11',
                            'phone'              => '259-784-2069',
                            'text'               => 'This is a test Edit Text',
                            'textarea'           => 'This is a test Edit TextArea',
                            'url'                => 'http://wwww.abc-edit.com',
                            'checkbox'           => array('value'  => '0'),
                            'currency'           => array('value'  => 40),
                            'picklist'           => array('value'  => 'b'),
                            'multiselect'        => array('values' => array(0 => null)),
                            'tagcloud'           => array('values' => array('reading', 'surfing')),
                            'countrypicklist'    => array('value'  => 'aaaa'),
                            'statepicklist'      => array('value'  => 'aaa1'),
                            'citypicklist'       => array('value'  => 'ab1'),
                            'radio'              => array('value'  => 'e'),
                            'date__Date'         => array('type'   => 'Today'),
                            'datetime__DateTime' => array('type'   => 'Today'));
        }
    }
?>