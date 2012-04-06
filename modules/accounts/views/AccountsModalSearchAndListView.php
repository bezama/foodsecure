<?php


    class AccountsModalSearchAndListView extends GridView
    {
        public function __construct($controllerId, $moduleId, $modalListLinkProvider,
                                    RedBeanModel $account, CDataProvider $dataProvider, $gridIdSuffix = null)
        {
            assert('$modalListLinkProvider instanceof ModalListLinkProvider');
            parent::__construct(2, 1);
            $this->setView(new AccountsModalSearchView($account, get_class($account), $gridIdSuffix), 0, 0);
            $this->setView(new AccountsModalListView(   $controllerId, $moduleId, get_class($account),
                                                        $modalListLinkProvider, $dataProvider, $gridIdSuffix), 1, 0);
        }

        public function isUniqueToAPage()
        {
            return true;
        }
    }
?>
