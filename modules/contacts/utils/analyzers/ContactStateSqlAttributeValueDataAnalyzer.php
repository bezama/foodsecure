<?php


    /**
     * Data analyzer for attributes that are a contact state.
     */
    class ContactStateSqlAttributeValueDataAnalyzer extends SqlAttributeValueDataAnalyzer
                                                implements DataAnalyzerInterface
    {
        public function runAndMakeMessages(AnalyzerSupportedDataProvider $dataProvider, $columnName)
        {
            assert('is_string($columnName)');
            $dropDownValues  = $this->resolveStates();
            $dropDownValues  = ArrayUtil::resolveArrayToLowerCase($dropDownValues);
            $data            = $dataProvider->getCountDataByGroupByColumnName($columnName);
            $count           = 0;
            foreach ($data as $valueCountData)
            {
                if ($valueCountData[$columnName] == null)
                {
                    continue;
                }
                if (!in_array(strtolower($valueCountData[$columnName]), $dropDownValues))
                {
                    $count++;
                }
            }
            if ($count > 0)
            {
                $label   = '{count} value(s) are not valid. ';
                $label  .= 'Rows that have these values will be skipped upon import.';
                $this->addMessage(Yii::t('Default', $label, array('{count}' => $count)));
            }
        }

        protected function resolveStates()
        {
            return ContactsUtil::getContactStateDataFromStartingStateOnAndKeyedById();
        }
    }
?>