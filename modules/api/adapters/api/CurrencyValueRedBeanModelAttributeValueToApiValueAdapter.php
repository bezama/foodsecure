<?php


    class CurrencyValueRedBeanModelAttributeValueToApiValueAdapter extends RedBeanModelAttributeValueToApiValueAdapter
    {
        public function resolveData(& $data)
        {
            assert('$this->model->{$this->attribute} instanceof CurrencyValue');
            $currencyValue = $this->model->{$this->attribute};
            if ($currencyValue->id > 0)
            {
                $data[$this->attribute] = array('id'         => $currencyValue->id,
                                                'value'      => $currencyValue->value,
                                                'rateToBase' => $currencyValue->rateToBase,
                                                'currency'   => array('id' => $currencyValue->currency->id));
            }
            else
            {
                $data[$this->attribute] = null;
            }
        }
    }
?>