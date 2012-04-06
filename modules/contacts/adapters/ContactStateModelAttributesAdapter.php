<?php


    /**
     * Adapter to set attributes from a contact state attribute form.
     */
    class ContactStateModelAttributesAdapter extends ModelAttributesAdapter
    {
        public function setAttributeMetadataFromForm(AttributeForm $attributeForm)
        {
            $modelClassName                  = get_class($this->model);
            $attributeName                   = $attributeForm->attributeName;
            $attributeLabels                 = $attributeForm->attributeLabels;
            $elementType                     = $attributeForm->getAttributeTypeName();
            $isRequired                      = (boolean)$attributeForm->isRequired;
            $isAudited                       = (boolean)$attributeForm->isAudited;
            $contactStatesData               = $attributeForm->contactStatesData;
            $contactStatesLabels             = $attributeForm->contactStatesLabels;
            $startingStateOrder              = (int)$attributeForm->startingStateOrder;
            $contactStatesDataExistingValues = $attributeForm->contactStatesDataExistingValues;
            if ($contactStatesDataExistingValues == null)
            {
                $contactStatesDataExistingValues = array();
            }

            if ($attributeForm instanceof ContactStateAttributeForm)
            {
                //update order on existing states.
                //delete removed states
                $states = ContactState::getAll('order');
                $stateNames = array();
                foreach ($states as $state)
                {
                    $stateNames[] = $state->name;
                    if (in_array($state->name, $contactStatesData))
                    {
                        $state->order = array_search($state->name, $contactStatesData);
                        $state->serializedLabels = $this->makeSerializedLabelsByLabelsAndOrder($contactStatesLabels,
                                                                                               (int)$state->order);
                        $saved        = $state->save();
                        assert('$saved');
                    }
                    elseif (in_array($state->name, $contactStatesDataExistingValues))
                    {
                        $order                   = array_search($state->name, $contactStatesDataExistingValues);
                        $state->name             = $contactStatesData[$order];
                        $state->order            = $order;
                        $state->serializedLabels = $this->makeSerializedLabelsByLabelsAndOrder($contactStatesLabels,
                                                                                               (int)$state->order);
                        $saved                   = $state->save();
                        assert('$saved');
                    }
                    else
                    {
                        $state->delete();
                    }
                }
                //add new states with correct order.
                foreach ($contactStatesData as $order => $name)
                {
                    if (!in_array($name, $stateNames))
                    {
                        $state                   = new ContactState();
                        $state->name             = $name;
                        $state->order            = $order;
                        $state->serializedLabels = $this->makeSerializedLabelsByLabelsAndOrder($contactStatesLabels,
                                                                                               (int)$order);
                        $saved                   = $state->save();
                        assert('$saved');
                    }
                }
                //Set starting state by order.
                ContactsUtil::setStartingStateByOrder($startingStateOrder);
                ModelMetadataUtil::addOrUpdateRelation($modelClassName,
                                                       $attributeName,
                                                       $attributeLabels,
                                                       $elementType,
                                                       $isRequired,
                                                       $isAudited,
                                                       'ContactState');
            }
            else
            {
                throw new NotSupportedException();
            }
        }

        protected function makeSerializedLabelsByLabelsAndOrder($contactStatesLabels, $order)
        {
            assert('is_array($contactStatesLabels) || $contactStatesLabels == null');
            assert('is_int($order)');
            if ($contactStatesLabels == null)
            {
                return null;
            }
            $unserializedLabels = array();
            foreach ($contactStatesLabels as $language => $languageLabelsByOrder)
            {
                if (isset($languageLabelsByOrder[$order]))
                {
                    $unserializedLabels[$language] = $languageLabelsByOrder[$order];
                }
            }
            if (count($unserializedLabels) == 0)
            {
                return null;
            }
            return serialize($unserializedLabels);
        }
    }
?>
