<?php


    /**
     * User interface element for managing related model relations for activities. This class supports a HAS_MANY
     * specifically for the 'contact' relation. This is utilized by the meeting model.
     *
     */
    class MultipleContactsForMeetingElement extends Element implements DerivedElementInterface
    {
        protected function renderControlNonEditable()
        {
            $content  = null;
            $contacts = $this->getExistingContactRelationsIdsAndLabels();
            foreach ($contacts as $contactData)
            {
                if ($content != null)
                {
                    $content .= ', ';
                }
                $content .= $contactData['name'];
            }
            return $content;
        }

        protected function renderControlEditable()
        {
            assert('$this->model instanceof Activity');
            $cClipWidget = new CClipWidget();
            $cClipWidget->beginClip("ModelElement");
            $cClipWidget->widget('ext.fosainc.framework.widgets.MultiSelectAutoComplete', array(
                'name'        => $this->getNameForIdField(),
                'id'          => $this->getIdForIdField(),
                'jsonEncodedIdsAndLabels'   => CJSON::encode($this->getExistingContactRelationsIdsAndLabels()),
                'sourceUrl'   => Yii::app()->createUrl('contacts/variableContactState/autoCompleteAllContactsForMultiSelectAutoComplete'),
                'htmlOptions' => array(
                    'disabled' => $this->getDisabledValue(),
                    ),
                'hintText' => Yii::t('Default', 'Type a ContactsModuleSingularLowerCaseLabel ' .
                                                'or LeadsModuleSingularLowerCaseLabel: name or email address',
                                LabelUtil::getTranslationParamsForAllModules())
            ));
            $cClipWidget->endClip();
            $content = $cClipWidget->getController()->clips['ModelElement'];
            return $content;
        }

        protected function renderError()
        {
        }

        protected function renderLabel()
        {
            return $this->resolveNonActiveFormFormattedLabel($this->getFormattedAttributeLabel());
        }

        protected function getFormattedAttributeLabel()
        {
            return Yii::app()->format->text(Yii::t('Default', 'Attendees'));
        }

         public static function getDisplayName()
        {
            return Yii::t('Default', 'Related ContactsModulePluralLabel and LeadsModulePluralLabel',
                       LabelUtil::getTranslationParamsForAllModules());
        }

        /**
         * Get the attributeNames of attributes used in
         * the derived element. For this element, there are no attributes from the model.
         * @return array - empty
         */
        public static function getModelAttributeNames()
        {
            return array();
        }

        protected function getNameForIdField()
        {
                return 'ActivityItemForm[Contact][ids]';
        }

        protected function getIdForIdField()
        {
            return 'ActivityItemForm_Contact_ids';
        }

        protected function getExistingContactRelationsIdsAndLabels()
        {
            $existingContacts = array();
            $modelDerivationPathToItem = ActivitiesUtil::getModelDerivationPathToItem('Contact');
            foreach ($this->model->activityItems as $item)
            {
                try
                {
                    $contact = $item->castDown(array($modelDerivationPathToItem));
                    if (get_class($contact) == 'Contact')
                    {
                        $existingContacts[] = array('id' => $contact->id,
                                                    'name' => self::renderHtmlContentLabelFromContactAndKeyword($contact, null));
                    }
                }
                catch (NotFoundException $e)
                {
                    //do nothing
                }
            }
            return $existingContacts;
        }

        /**
         * Given a contact model and a keyword, render the strval of the contact and the matched email address
         * that the keyword matches. If the keyword does not match any email addresses on the contact, render the
         * primary email if it exists. Otherwise just render the strval contact.
         * @param object $contact - model
         * @param string $keyword
         */
        public static function renderHtmlContentLabelFromContactAndKeyword($contact, $keyword)
        {
            assert('$contact instanceof Contact && $contact->id > 0');
            assert('$keyword == null || is_string($keyword)');

            if (substr($contact->secondaryEmail->emailAddress, 0, strlen($keyword)) === $keyword)
            {
                $emailAddressToUse = $contact->secondaryEmail->emailAddress;
            }
            else
            {
                $emailAddressToUse = $contact->primaryEmail->emailAddress;
            }
            if ($emailAddressToUse != null)
            {
                return strval($contact) . '&#160&#160<b>' . strval($emailAddressToUse) . '</b>';
            }
            else
            {
                return strval($contact);
            }
        }
    }
?>