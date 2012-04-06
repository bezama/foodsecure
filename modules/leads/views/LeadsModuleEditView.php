<?php


    class LeadsModuleEditView extends GlobalSearchEnabledModuleEditView
    {
        public static function getDefaultMetadata()
        {
            $metadata = parent::getDefaultMetadata();
            $metadata['global']['panels'][0]['rows'][] =
                array('cells' =>
                    array(
                        array(
                            'elements' => array(
                                array('attributeName' => 'convertToAccountSetting', 'type' => 'LeadsConvertToAccountRadio'),
                            ),
                        ),
                    ),
                );
            return $metadata;
        }
    }
?>