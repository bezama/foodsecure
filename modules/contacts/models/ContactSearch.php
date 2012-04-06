<?php


    class ContactSearch
    {
        /**
         * For a give Contact name, run a partial search by
         * full name and retrieve contact models.
         *
         */
        public static function getContactsByPartialFullName($partialName, $pageSize, $stateMetadataAdapterClassName = null)
        {
            assert('is_string($partialName)');
            assert('is_int($pageSize)');
            assert('$stateMetadataAdapterClassName == null || is_string($stateMetadataAdapterClassName)');
            $personTableName   = RedBeanModel::getTableName('Person');
            $joinTablesAdapter = new RedBeanModelJoinTablesQueryAdapter('Contact');
            if (!$joinTablesAdapter->isTableInFromTables('person'))
            {
                $joinTablesAdapter->addFromTableAndGetAliasName($personTableName, "{$personTableName}_id");
            }
            $metadata = array('clauses' => array(), 'structure' => '');
            if ($stateMetadataAdapterClassName != null)
            {
                $stateMetadataAdapter = new $stateMetadataAdapterClassName($metadata);
                $metadata = $stateMetadataAdapter->getAdaptedDataProviderMetadata();
                $metadata['structure'] = '(' . $metadata['structure'] . ')';
            }
            $where  = RedBeanModelDataProvider::makeWhere('Contact', $metadata, $joinTablesAdapter);
            if ($where != null)
            {
                $where .= 'and';
            }
            $where .= self::getWherePartForPartialNameSearchByPartialName($partialName);
            return Contact::getSubset($joinTablesAdapter, null, $pageSize, $where, "person.firstname, person.lastname");
        }

        /**
         * For a give Contact name or email address, run a partial search by
         * full name and email address and retrieve contact models.
         *
         */
        public static function getContactsByPartialFullNameOrAnyEmailAddress($partialNameOrEmailAddress, $pageSize,
                                                                             $stateMetadataAdapterClassName = null)
        {
            assert('is_string($partialNameOrEmailAddress)');
            assert('is_int($pageSize)');
            assert('$stateMetadataAdapterClassName == null || is_string($stateMetadataAdapterClassName)');
            $metadata = array();
            $metadata['clauses'] = array(
                1 => array(
                    'attributeName'        => 'primaryEmail',
                    'relatedAttributeName' => 'emailAddress',
                    'operatorType'         => 'startsWith',
                    'value'                => $partialNameOrEmailAddress,
                ),
                2 => array(
                    'attributeName'        => 'secondaryEmail',
                    'relatedAttributeName' => 'emailAddress',
                    'operatorType'         => 'startsWith',
                    'value'                => $partialNameOrEmailAddress,
                ),
            );
            $metadata['structure'] = '((1 or 2) or partialnamesearch)';
            $joinTablesAdapter   = new RedBeanModelJoinTablesQueryAdapter('Contact');
            if ($stateMetadataAdapterClassName != null)
            {
                $stateMetadataAdapter = new $stateMetadataAdapterClassName($metadata);
                $metadata = $stateMetadataAdapter->getAdaptedDataProviderMetadata();
            }
            $where  = RedBeanModelDataProvider::makeWhere('Contact', $metadata, $joinTablesAdapter);
            $partialNameWherePart = self::getWherePartForPartialNameSearchByPartialName($partialNameOrEmailAddress);
            $where  = strtr(strtolower($where), array('partialnamesearch' => $partialNameWherePart));
            return Contact::getSubset($joinTablesAdapter, null, $pageSize, $where, "person.firstname, person.lastname");
        }

        protected static function getWherePartForPartialNameSearchByPartialName($partialName)
        {
            assert('is_string($partialName)');
            $fullNameSql = DatabaseCompatibilityUtil::concat(array('person.firstname',
                                                                   '\' \'',
                                                                   'person.lastname'));
            return "      (person.firstname      like '$partialName%' or "    .
                   "       person.lastname       like '$partialName%' or "    .
                   "       $fullNameSql like '$partialName%') ";
        }
    }
?>
