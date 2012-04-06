<?php


    /**
     * Helper functions to assist with testing designer walkthroughs specifically for leads search form parameters.
     */
    class LeadsDesignerWalkthroughHelperUtil
    {
        /**
         * This function returns the necessary get parameters for the lead search form
         * based on the lead edited data.
         */
        public static function fetchLeadsSearchFormGetData($leadStateId, $superUserId)
        {
            return  array(
                            'fullName'           => 'Sarah Williams Edit',
                            'officePhone'        => '739-742-3005',
                            'anyPostalCode'      => '95131',
                            'department'         => 'Sales Edit',
                            'companyName'        => 'ABC Telecom Edit',
                            'industry'           => array('value' => 'Banking'),
                            'website'            => 'http://www.companyedit.com',
                            'anyCountry'         => 'USA',
                            'anyInvalidEmail'    => array('value' => '0'),
                            'anyEmail'           => 'info@myNewLeadEdit.com',
                            'anyOptOutEmail'     => array('value' => '0'),
                            'ownedItemsOnly'     => '1',
                            'anyStreet'          => '26378 South Arlington Ave',
                            'anyCity'            => 'San Jose',
                            'anyState'           => 'CA',
                            'state'              => array('id' => $leadStateId),
                            'owner'              => array('id' => $superUserId),
                            'firstName'          => 'Sarah',
                            'lastName'           => 'Williams Edit',
                            'jobTitle'           => 'Sales Director Edit',
                            'officeFax'          => '255-454-1914',
                            'title'              => array('value' => 'Mrs.'),
                            'source'             => array('value' => 'Inbound Call'),
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
    }
?>