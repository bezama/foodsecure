<?php


    class ContactsModalSearchAndListView extends GridView
    {
        public function __construct($controllerId, $moduleId, $modalListLinkProvider,
                                    RedBeanModel $contact, CDataProvider $dataProvider, $gridIdSuffix = null)
        {
            assert('$modalListLinkProvider instanceof ModalListLinkProvider');
            parent::__construct(2, 1);
            $this->setView(new ContactsModalSearchView($contact, get_class($contact), $gridIdSuffix), 0, 0);
            $this->setView(new ContactsModalListView($controllerId, $moduleId, get_class($contact),
                                                     $modalListLinkProvider,  $dataProvider, $gridIdSuffix), 1, 0);
        }

        public function isUniqueToAPage()
        {
            return true;
        }
    }
?>
