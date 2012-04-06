<?php


    /**
     * Data analyzer for attributes that are a contact state.
     */
    class ContactStateBatchAttributeValueDataAnalyzer extends BatchAttributeValueDataAnalyzer
                                                      implements DataAnalyzerInterface
    {
        protected $states;

        public function __construct($modelClassName, $attributeName)
        {
            parent:: __construct($modelClassName, $attributeName);
            assert('$attributeName == null');
            $states       = $this->resolveStates();
            $this->states = ArrayUtil::resolveArrayToLowerCase($states);
        }

        public function runAndMakeMessages(AnalyzerSupportedDataProvider $dataProvider, $columnName)
        {
            assert('is_string($columnName)');
            $this->processAndMakeMessage($dataProvider, $columnName);
        }

        protected function analyzeByValue($value)
        {
            if ($value != null && !in_array(strtolower($value), $this->states))
            {
                $this->messageCountData[static::INVALID] ++;
            }
        }

        protected function makeMessages()
        {
            $invalid  = $this->messageCountData[static::INVALID];
            if ($invalid > 0)
            {
                $label   = '{count} value(s) are not valid. ';
                $label  .= 'Rows that have these values will be skipped upon import.';
                $this->addMessage(Yii::t('Default', $label, array('{count}' => $invalid)));
            }
        }

        protected function resolveStates()
        {
            return ContactsUtil::getContactStateDataFromStartingStateOnAndKeyedById();
        }
    }
?>