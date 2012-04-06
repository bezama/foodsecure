<?php


    class DropDownRedBeanModelAttributeValueToApiValueAdapter extends RedBeanModelAttributeValueToApiValueAdapter
    {
        public function resolveData(& $data)
        {
            assert('$this->model->{$this->attribute} instanceof CustomField');
            if ($this->model->{$this->attribute}->id > 0)
            {
                $data[$this->attribute] = array('id'         => $this->model->{$this->attribute}->id,
                                                'value'      => $this->model->{$this->attribute}->value);
            }
            else
            {
                $data[$this->attribute] = null;
            }
        }
    }
?>