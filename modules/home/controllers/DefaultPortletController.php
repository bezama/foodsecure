<?php


    class HomeDefaultPortletController extends fosaPortletController
    {
        public function actionAddList()
        {
            $view = new ModalView($this,
                new HomeDashboardPortletSelectionView(
                    $this->getId(),
                    $this->getModule()->getId(),
                    $_GET['dashboardId'],
                    $_GET['uniqueLayoutId']
                    ),
                'modalContainer',
                Yii::t('Default', 'Add Portlet'));
            echo $view->render();
        }

        /**
         * Add portlet to first column, first position
         * and if there are other portlets in the first
         * column, shift their postion by 1 to accomodate
         * the new portlet
         *
         */
        public function actionAdd()
        {
            assert('!empty($_GET["uniqueLayoutId"])');
            assert('!empty($_GET["portletType"])');
            $portletCollection = Portlet::getByLayoutIdAndUserSortedByColumnIdAndPosition($_GET['uniqueLayoutId'], Yii::app()->user->userModel->id, array());
            if (!empty($portletCollection))
            {
                foreach ($portletCollection[1] as $position => $portlet)
                {
                        $portlet->position = $portlet->position + 1;
                        $portlet->save();
                }
            }
            if (!empty($_GET['dashboardId']))
            {
                $dashboardId = $_GET['dashboardId'];
            }
            else
            {
                $dashboardId = '';
            }
            Portlet::makePortletUsingViewType($_GET['portletType'], $_GET['uniqueLayoutId'], Yii::app()->user->userModel);
            $this->redirect(array('default/dashboardDetails', 'id' => $dashboardId));
        }
    }
?>