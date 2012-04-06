<?php


    /**
     * A model to store information about jobs that are currently running.
     */
    class JobInProcess extends Item
    {
        public static function getByType($type)
        {
            assert('is_string($type) && $type != ""');
            $searchAttributeData = array();
            $searchAttributeData['clauses'] = array(
                1 => array(
                    'attributeName'        => 'type',
                    'operatorType'         => 'equals',
                    'value'                => $type,
                ),
            );
            $searchAttributeData['structure'] = '1';
            $joinTablesAdapter = new RedBeanModelJoinTablesQueryAdapter('JobInProcess');
            $where  = RedBeanModelDataProvider::makeWhere('JobInProcess', $searchAttributeData, $joinTablesAdapter);
            $models = self::getSubset($joinTablesAdapter, null, null, $where, null);
            if (count($models) > 1)
            {
                throw new NotSupportedException();
            }
            if (count($models) == 0)
            {
                throw new NotFoundException();
            }
            return $models[0];
        }

        public function __toString()
        {
            if ($this->type == null)
            {
                return null;
            }
            return JobsUtil::resolveStringContentByType($this->type);
        }

        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata[__CLASS__] = array(
                'members' => array(
                    'type',

                ),
                'rules' => array(
                    array('type', 'required'),
                    array('type', 'type', 'type' => 'string'),
                    array('type', 'length',  'min'  => 3, 'max' => 64),
                ),
                'defaultSortAttribute' => 'createdDateTime',
                'noAudit' => array(
                    'type',
                )
            );
            return $metadata;
        }

        public static function isTypeDeletable()
        {
            return true;
        }
    }
?>