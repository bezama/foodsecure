<?php


    /**
     * Display the name and hidden id of the account model.
     * Displays a select button and auto-complete input
     */
    class AccountNameIdElement extends NameIdElement
    {
        protected static $moduleId = 'accounts';

        protected $idAttributeId = 'accountId';

        protected $nameAttributeName = 'accountName';
    }
?>