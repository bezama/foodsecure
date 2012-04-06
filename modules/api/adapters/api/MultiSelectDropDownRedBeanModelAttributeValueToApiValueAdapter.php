<?php


    class MultiSelectDropDownRedBeanModelAttributeValueToApiValueAdapter extends DropDownRedBeanModelAttributeValueToApiValueAdapter
    {
        public function resolveData(& $data)
        {
            assert('$this->model->{$this->attribute} instanceof OwnedMultipleValuesCustomField');
            $customFieldValues = $this->model->{$this->attribute}->values;
            if (count($customFieldValues) > 0)
            {
                foreach ($customFieldValues as $customFieldValue)
                {
                    if (isset($customFieldValue->value) && $customFieldValue->value != '')
                    {
                        $data[$this->attribute]['values'][] = $customFieldValue->value;
                    }
                }
                if (!isset($data[$this->attribute]['values']))
                {
                    $data[$this->attribute]['values'] = null;
                }
            }
            else
            {
                $data[$this->attribute]['values'] = null;
            }
        }
    }
?>