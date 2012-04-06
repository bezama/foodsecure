<?php


    /**
     * Utilized by module views that extend ListView
     * to provide abstracted column element information
     * that can be translated into one of the available
     * GridView widgets in Yii.
     */
    abstract class RedBeanModelAttributeValueToApiValueAdapter
    {
        protected $model;

        protected $attribute;

        public function __construct($model, $attribute)
        {
            $this->model     = $model;
            $this->attribute = $attribute;
        }

        /**
         * Resolve data
         * @param array $data
         */
        public function resolveData(& $data)
        {
            $data[$this->attribute] = $this->model->{$this->attribute};
            return;
        }
    }
?>
