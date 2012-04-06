<?php


    class CurrencyRedBeanModelAttributeValueToApiValueAdapter extends RedBeanModelAttributeValueToApiValueAdapter
    {
        public function resolveData(& $data)
        {
            assert('$this->model->{$this->attribute} instanceof Currency');
            $currency = $this->model->{$this->attribute};
            if ($currency->id > 0)
            {
                $data[$this->attribute] = array('id'         => $currency->id);
            }
            else
            {
                $data[$this->attribute] = null;
            }
        }
    }
?>