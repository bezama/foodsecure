<?php


    class LeadsDefaultController extends fosaModuleController
    {
        public function filters()
        {
            return array_merge(parent::filters(),
                array(
                    array(
                        fosaBaseController::RIGHTS_FILTER_PATH . ' + convert, saveConvertedContact',
                        'moduleClassName' => 'LeadsModule',
                        'rightName' => LeadsModule::RIGHT_CONVERT_LEADS,
                   ),
                    array(
                        fosaBaseController::REQUIRED_ATTRIBUTES_FILTER_PATH . ' + create, edit',
                        'moduleClassName' => get_class($this->getModule()),
                        'viewClassName'   => 'LeadEditAndDetailsView',
                   ),
               )
            );
        }

        public function actionList()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'listPageSize', get_class($this->getModule()));
            $contact  = new Contact(false);
            $searchForm = new LeadsSearchForm($contact);
            $dataProvider = $this->makeSearchFilterListDataProvider(
                $searchForm,
                'Contact',
                'LeadsFilteredList',
                $pageSize,
                Yii::app()->user->userModel->id,
                'LeadsStateMetadataAdapter'
            );
            $searchFilterListView = $this->makeSearchFilterListView(
                $searchForm,
                'LeadsFilteredList',
                $pageSize,
                Yii::t('Default', 'Leads'),
                Yii::app()->user->userModel->id,
                $dataProvider
            );
            $view = new LeadsPageView($this, $searchFilterListView);
            echo $view->render();
        }

        public function actionDetails($id)
        {
            $contact = Contact::getById(intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserReadModel($contact);
            if (!LeadsUtil::isStateALead($contact->state))
            {
                $urlParams = array('/contacts/' . $this->getId() . '/details', 'id' => $contact->id);
                $this->redirect($urlParams);
            }
            else
            {
                AuditEvent::logAuditEvent('fosaModule', fosaModule::AUDIT_EVENT_ITEM_VIEWED, strval($contact), $contact);
                $detailsAndRelationsView = $this->makeDetailsAndRelationsView($contact, 'LeadsModule',
                                                                              'LeadDetailsAndRelationsView',
                                                                              Yii::app()->request->getRequestUri());
                $view = new LeadsPageView($this, $detailsAndRelationsView);
                echo $view->render();
            }
        }

        public function actionCreate()
        {
            $titleBarAndEditView = $this->makeTitleBarAndEditAndDetailsView(
                    $this->attemptToSaveModelFromPost(new Contact()), 'Edit',
                    'LeadTitleBarAndEditAndDetailsView'
            );
            $view = new LeadsPageView($this, $titleBarAndEditView);
            echo $view->render();
        }

        public function actionEdit($id, $redirectUrl = null)
        {
            $contact = Contact::getById(intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($contact);
            if (!LeadsUtil::isStateALead($contact->state))
            {
                $urlParams = array('/contacts/' . $this->getId() . '/edit', 'id' => $contact->id);
                $this->redirect($urlParams);
            }
            else
            {
                $view = new LeadsPageView($this,
                    $this->makeTitleBarAndEditAndDetailsView(
                        $this->attemptToSaveModelFromPost($contact, $redirectUrl), 'Edit',
                        'LeadTitleBarAndEditAndDetailsView'
                    )
                );
                echo $view->render();
            }
        }

        /**
         * Action for displaying a mass edit form and also action when that form is first submitted.
         * When the form is submitted, in the event that the quantity of models to update is greater
         * than the pageSize, then once the pageSize quantity has been reached, the user will be
         * redirected to the makeMassEditProgressView.
         * In the mass edit progress view, a javascript refresh will take place that will call a refresh
         * action, usually massEditProgressSave.
         * If there is no need for a progress view, then a flash message will be added and the user will
         * be redirected to the list view for the model.  A flash message will appear providing information
         * on the updated records.
         * @see Controler->makeMassEditProgressView
         * @see Controller->processMassEdit
         * @see
         */
        public function actionMassEdit()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'massEditProgressPageSize');
            $activeAttributes = $this->resolveActiveAttributesFromMassEditPost();
            $contact = new Contact(false);
            $dataProvider = $this->getDataProviderByResolvingSelectAllFromGet(
                new LeadsSearchForm($contact),
                'Contact',
                $pageSize,
                Yii::app()->user->userModel->id,
                'LeadsFilteredList',
                'LeadsStateMetadataAdapter');
            $selectedRecordCount = $this->getSelectedRecordCountByResolvingSelectAllFromGet($dataProvider);
            $contact = $this->processMassEdit(
                $pageSize,
                $activeAttributes,
                $selectedRecordCount,
                'LeadsPageView',
                $contact,
                Yii::t('Default', 'Leads'),
                $dataProvider
            );
            $titleBarAndMassEditView = $this->makeTitleBarAndMassEditView(
                $contact,
                $activeAttributes,
                $selectedRecordCount,
                Yii::t('Default', 'Leads')
            );
            $view = new LeadsPageView($this, $titleBarAndMassEditView);
            echo $view->render();
        }

        /**
         * Action called in the event that the mass edit quantity is larger than the pageSize.
         * This action is called after the pageSize quantity has been updated and continues to be
         * called until the mass edit action is complete.  For example, if there are 20 records to update
         * and the pageSize is 5, then this action will be called 3 times.  The first 5 are updated when
         * the actionMassEdit is called upon the initial form submission.
         */
        public function actionMassEditProgressSave()
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'massEditProgressPageSize');
            $contact = new Contact(false);
            $dataProvider = $this->getDataProviderByResolvingSelectAllFromGet(
                new LeadsSearchForm($contact),
                'Contact',
                $pageSize,
                Yii::app()->user->userModel->id,
                'LeadsFilteredList',
                'LeadsStateMetadataAdapter'
            );
            $this->processMassEditProgressSave(
                'Contact',
                $pageSize,
                Yii::t('Default', 'Leads'),
                $dataProvider
            );
        }

        public function actionConvert($id)
        {
            assert('!empty($id)');
            $contact                 = Contact::getById(intval($id));
            if (!LeadsUtil::isStateALead($contact->state))
            {
                $urlParams = array('/contacts/' . $this->getId() . '/details', 'id' => $contact->id);
                $this->redirect($urlParams);
            }
            $convertToAccountSetting = LeadsModule::getConvertToAccountSetting();
            $selectAccountForm       = new AccountSelectForm();
            $account                 = new Account();
            ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($contact);

            $userCanAccessContacts = RightsUtil::canUserAccessModule('ContactsModule', Yii::app()->user->userModel);
            $userCanAccessAccounts = RightsUtil::canUserAccessModule('AccountsModule', Yii::app()->user->userModel);
            $userCanCreateAccount  = RightsUtil::doesUserHaveAllowByRightName('AccountsModule',
                                     AccountsModule::RIGHT_CREATE_ACCOUNTS, Yii::app()->user->userModel);
            LeadsControllerSecurityUtil::
            resolveCanUserProperlyConvertLead($userCanAccessContacts, $userCanAccessAccounts, $convertToAccountSetting);
            if (isset($_POST['AccountSelectForm']))
            {
                $selectAccountForm->setAttributes($_POST['AccountSelectForm']);
                if ($selectAccountForm->validate())
                {
                    $account = Account::getById(intval($selectAccountForm->accountId));
                    $this->actionSaveConvertedContact($contact, $account);
                }
            }
            elseif (isset($_POST['Account']))
            {
                $account = LeadsUtil::AttributesToAccountWithNoPostData($contact, $account, $_POST['Account']);
                $account->setAttributes($_POST['Account']);
                if ($account->save())
                {
                    $this->actionSaveConvertedContact($contact, $account);
                }
            }
            elseif (isset($_POST['AccountSkip']) ||
                $convertToAccountSetting == LeadsModule::CONVERT_NO_ACCOUNT ||
                ($convertToAccountSetting == LeadsModule::CONVERT_ACCOUNT_NOT_REQUIRED && !$userCanAccessAccounts)
                )
            {
                $this->actionSaveConvertedContact($contact);
            }
            else
            {
                $account = LeadsUtil::AttributesToAccount($contact, $account);
            }
            $convertView = new LeadConvertView(
                $this->getId(),
                $this->getModule()->getId(),
                $contact->id,
                strval($contact),
                $selectAccountForm,
                $account,
                $convertToAccountSetting,
                $userCanCreateAccount
            );
            $view = new LeadsPageView($this, $convertView);
            echo $view->render();
        }

        protected function actionSaveConvertedContact($contact, $account = null)
        {
            $contact->account = $account;
            $contact->state   = ContactsUtil::getStartingState();
            if ($contact->save())
            {
                Yii::app()->user->setFlash('notification',
                    Yii::t('Default', 'LeadsModuleSingularLabel successfully converted.',
                                           LabelUtil::getTranslationParamsForAllModules())
                );
                $this->redirect(array('/contacts/default/details', 'id' => $contact->id));
            }
            Yii::app()->user->setFlash('notification',
                Yii::t('Default', 'LeadsModuleSingularLabel was not converted. An error occurred.')
            );
            $this->redirect(array('default/details', 'id' => $contact->id));
            Yii::app()->end(0, false);
        }

        public function actionModalList()
        {
            $modalListLinkProvider = new SelectFromRelatedEditModalListLinkProvider(
                                            $_GET['modalTransferInformation']['sourceIdFieldId'],
                                            $_GET['modalTransferInformation']['sourceNameFieldId']
            );
            echo ModalSearchListControllerUtil::setAjaxModeAndRenderModalSearchList($this, $modalListLinkProvider,
                                                Yii::t('Default', 'LeadsModuleSingularLabel Search',
                                                LabelUtil::getTranslationParamsForAllModules()),
                                                'LeadsStateMetadataAdapter');
        }

        public function actionDelete($id)
        {
            $contact = Contact::GetById(intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserDeleteModel($contact);
            if (!LeadsUtil::isStateALead($contact->state))
            {
                $urlParams = array('/contacts/' . $this->getId() . '/delete', 'id' => $contact->id);
                $this->redirect($urlParams);
            }
            else
            {
                $contact->delete();
                $this->redirect(array($this->getId() . '/index'));
            }
        }

        /**
         * Override to always add contact state filter on search results.
         */
        public function actionAutoComplete($term)
        {
            $pageSize = Yii::app()->pagination->resolveActiveForCurrentUserByType(
                            'autoCompleteListPageSize', get_class($this->getModule()));
            $autoCompleteResults = ContactAutoCompleteUtil::getByPartialName($term, $pageSize, 'LeadsStateMetadataAdapter');
            echo CJSON::encode($autoCompleteResults);
        }
    }
?>
