<?php


    class TextAreaRedBeanModelAttributeValueToApiValueAdapter extends TextRedBeanModelAttributeValueToApiValueAdapter
    {
        public function renderGridViewData()
        {
            return array(
                'name' => $this->attribute,
                'type' => 'Ntext',
            );
        }
    }
?>