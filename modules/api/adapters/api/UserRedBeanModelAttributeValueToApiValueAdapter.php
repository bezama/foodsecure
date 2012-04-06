<?php


    class UserRedBeanModelAttributeValueToApiValueAdapter extends RedBeanModelAttributeValueToApiValueAdapter
    {
        public function resolveData(& $data)
        {
            assert('$this->model->{$this->attribute} instanceof User');
            if ($this->model->{$this->attribute}->id > 0)
            {
                $data[$this->attribute] = array('id'         => $this->model->{$this->attribute}->id,
                                                'username'   => $this->model->{$this->attribute}->username);
            }
            else
            {
                $data[$this->attribute] = null;
            }
        }
    }
?>