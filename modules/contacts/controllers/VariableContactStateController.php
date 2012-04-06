<?php


    /**
     * This controller handles actions that rely on more than just the contact module for security checks. Most likely
     * it includes the lead module as well.  Controller security checks are done within the actions and not done using
     * a filter. You can add filters if you would like.
     *
     */
    class ContactsVariableContactStateController extends Controller
    {
        /**
         * Given a partial name search for all contacts regardless of contact state unless the current user has security
         * restrictions on some states.  If the adapter resolver returns false, then the
         * user does not have access to the Leads or Contacts module.
         * JSON encode the resulting array of contacts.
         */
        public function actionAutoCompleteAllContacts($term)
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'autoCompleteListPageSize', get_class($this->getModule()));
            $adapterName  = ContactsUtil::resolveContactStateAdapterByModulesUserHasAccessTo('LeadsModule',
                                                                                        'ContactsModule',
                                                                                         Yii::app()->user->userModel);
            if ($adapterName === false)
            {
                $messageView = new AccessFailureView();
                $view        = new AccessFailurePageView($messageView);
                echo $view->render();
                Yii::app()->end(0, false);
            }
            $autoCompleteResults = ContactAutoCompleteUtil::getByPartialName($term, $pageSize, $adapterName);
            echo CJSON::encode($autoCompleteResults);
        }

        /**
         * Given a partial name or e-mail address, search for all contacts regardless of contact state unless the
         * current user has security restrictions on some states.  If the adapter resolver returns false, then the
         * user does not have access to the Leads or Contacts module.
         * JSON encode the resulting array of contacts.
         */
        public function actionAutoCompleteAllContactsForMultiSelectAutoComplete($term)
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'autoCompleteListPageSize', get_class($this->getModule()));
            $adapterName  = ContactsUtil::resolveContactStateAdapterByModulesUserHasAccessTo('LeadsModule',
                                                                                        'ContactsModule',
                                                                                         Yii::app()->user->userModel);
            if ($adapterName === false)
            {
                $messageView = new AccessFailureView();
                $view        = new AccessFailurePageView($messageView);
                echo $view->render();
                Yii::app()->end(0, false);
            }
            $contacts = ContactSearch::getContactsByPartialFullNameOrAnyEmailAddress($term, $pageSize, $adapterName);
            $autoCompleteResults  = array();
            foreach ($contacts as $contact)
            {
                $autoCompleteResults[] = array(
                    'id'   => $contact->id,
                    'name' => MultipleContactsForMeetingElement::renderHtmlContentLabelFromContactAndKeyword($contact, $term)
                );
            }
            echo CJSON::encode($autoCompleteResults);
        }

        public function actionModalListAllContacts()
        {
            $modalListLinkProvider = new SelectFromRelatedEditModalListLinkProvider(
                                            $_GET['modalTransferInformation']['sourceIdFieldId'],
                                            $_GET['modalTransferInformation']['sourceNameFieldId']
            );
            $adapterName  = ContactsUtil::resolveContactStateAdapterByModulesUserHasAccessTo('LeadsModule',
                                                                                        'ContactsModule',
                                                                                         Yii::app()->user->userModel);
            if ($adapterName === false)
            {
                $messageView = new AccessFailureView();
                $view        = new AccessFailurePageView($messageView);
                echo $view->render();
                Yii::app()->end(0, false);
            }
            echo ModalSearchListControllerUtil::setAjaxModeAndRenderModalSearchList($this, $modalListLinkProvider,
                                                Yii::t('Default', 'ContactsModuleSingularLabel Search',
                                                LabelUtil::getTranslationParamsForAllModules()),
                                                $adapterName);
        }
    }
?>
