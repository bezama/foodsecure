<?php


    /**
     * Helper class used to convert models into arrays
     */
    class RedBeanModelToApiDataUtil
    {
        /**
         * @var RedBeanModel
         */
        protected $model;

        public function __construct($model)
        {
            assert('$model->id > 0');
            $this->model = $model;
        }

        /**
         *
         * Get model properties as array.
         * return array
         */
        public function getData()
        {
            $data       = array();
            $data['id'] = $this->model->id;
            $retrievableAttributes = static::resolveRetrievableAttributesByModel($this->model);
            foreach ($this->model->getAttributes($retrievableAttributes) as $attributeName => $notUsed)
            {
                $type             = ModelAttributeToMixedApiTypeUtil::getType($this->model, $attributeName);
                $adapterClassName = $type . 'RedBeanModelAttributeValueToApiValueAdapter';
                if ($type != null && @class_exists($adapterClassName) &&
                   !($this->model->isRelation($attributeName) && $this->model->getRelationType($attributeName) !=
                      RedBeanModel::HAS_ONE))
                {
                    $adapter = new $adapterClassName($this->model, $attributeName);
                    $adapter->resolveData($data);
                }
                elseif ($this->model->isOwnedRelation($attributeName) &&
                       ($this->model->getRelationType($attributeName) == RedBeanModel::HAS_ONE ||
                        $this->model->getRelationType($attributeName) == RedBeanModel::HAS_MANY_BELONGS_TO))
                {
                    if ($this->model->{$attributeName}->id > 0)
                    {
                        $util = new RedBeanModelToApiDataUtil($this->model->{$attributeName});
                        $relatedData          = $util->getData();
                        $data[$attributeName] = $relatedData;
                    }
                    else
                    {
                        $data[$attributeName] = null;
                    }
                 }
                 //We don't want to list properties from CustomFieldData objects
                 //This is also case fo related models, not only for custom fields
                 elseif ($this->model->isRelation($attributeName) &&
                         $this->model->getRelationType($attributeName) == RedBeanModel::HAS_ONE)
                 {
                    if ($this->model->{$attributeName}->id > 0)
                    {
                        $data[$attributeName] = array('id' => $this->model->{$attributeName}->id);
                    }
                    else
                    {
                        $data[$attributeName] = null;
                    }
                 }
            }
            return $data;
        }

        /**
         * Return array of retrievable model attributes
         * @return array
         */
        protected static function resolveRetrievableAttributesByModel($model)
        {
            $retrievableAttributeNames = array();
           foreach ($model->attributeNames() as $name)
           {
               try
               {
                   $value = $model->{$name};
                   $retrievableAttributeNames[] = $name;
               }
               catch (Exception $e)
               {
               }
           }
            return $retrievableAttributeNames;
        }
    }
?>