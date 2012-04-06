<?php


    class HomeDefaultController extends fosaBaseController
    {
        public function filters()
        {
            return array_merge(parent::filters(),
                array(
                    array(
                        fosaBaseController::RIGHTS_FILTER_PATH . ' - index',
                        'moduleClassName' => 'HomeModule',
                        'rightName' => HomeModule::RIGHT_ACCESS_DASHBOARDS,
                   ),
                    array(
                        fosaBaseController::RIGHTS_FILTER_PATH . ' + createDashboard',
                        'moduleClassName' => 'HomeModule',
                        'rightName' => HomeModule::RIGHT_CREATE_DASHBOARDS,
                   ),
                    array(
                        fosaBaseController::RIGHTS_FILTER_PATH . ' + deleteDashboard',
                        'moduleClassName' => 'HomeModule',
                        'rightName' => HomeModule::RIGHT_DELETE_DASHBOARDS,
                   ),
               )
            );
        }

        public function actionIndex()
        {
            if (RightsUtil::doesUserHaveAllowByRightName(
                'HomeModule',
                HomeModule::RIGHT_ACCESS_DASHBOARDS,
                Yii::app()->user->userModel))
            {
                $this->actionDashboardDetails(-1);
            }
            else
            {
                $view = new HomePageView($this, new WelcomeView());
                echo $view->render();
            }
        }

        public function actionDashboardDetails($id)
        {
            if (intval($id) > 0)
            {
                $dashboard = Dashboard::getById(intval($id));
                $layoutId  = $dashboard->layoutId;
            }
            else
            {
                $dashboard = Dashboard::getByLayoutIdAndUser(Dashboard::DEFAULT_USER_LAYOUT_ID, Yii::app()->user->userModel);
                $layoutId  = $dashboard->layoutId;
            }
            $params = array(
                'controllerId' => $this->getId(),
                'moduleId'     => $this->getModule()->getId(),
            );
            ControllerSecurityUtil::resolveAccessCanCurrentUserReadModel($dashboard);
            $view = new HomePageView($this, new HomeTitleBarAndDashboardView(
                $this->getId(),
                $this->getModule()->getId(),
                'HomeDashboard' . $layoutId,
                $dashboard,
                $params)
            );
            echo $view->render();
        }

        public function actionCreateDashboard()
        {
            $dashboard = new Dashboard();
            if (isset($_POST['Dashboard']))
            {
                $dashboard->owner = Yii::app()->user->userModel;
                $dashboard->layoutId = Dashboard::getNextLayoutId();
                $dashboard->setAttributes($_POST['Dashboard']);
                assert('in_array($dashboard->layoutType, array_keys(Dashboard::getLayoutTypesData()))');
                if ($dashboard->save())
                {
                    $this->redirect(array('default/dashboardDetails', 'id' => $dashboard->id));
                }
            }
            $view = new HomePageView($this, new DashboardTitleBarAndEditView($this->getId(), $this->getModule()->getId(), $dashboard));
            echo $view->render();
        }

        /**
         * Only supports saving 4 layoutTypes (max 2 column)
         *
         */
        public function actionEditDashboard($id)
        {
            $id = intval($id);
            $dashboard = Dashboard::getById(intval($id));
            ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($dashboard);
            if (isset($_POST['Dashboard']))
            {
                $oldLayoutType = $dashboard->layoutType;
                $dashboard->setAttributes($_POST['Dashboard']);
                assert('in_array($dashboard->layoutType, array_keys(Dashboard::getLayoutTypesData()))');
                if ($dashboard->save())
                {
                    if ($oldLayoutType != $dashboard->layoutType && $dashboard->layoutType == '100')
                    {
                        $uniqueLayoutId = 'HomeDashboard' . $dashboard->layoutId;
                        $portletCollection = Portlet::getByLayoutIdAndUserSortedByColumnIdAndPosition($uniqueLayoutId, Yii::app()->user->userModel->id, array());
                        Portlet::shiftPositionsBasedOnColumnReduction($portletCollection, 1);
                    }
                    $this->redirect(array('default/dashboardDetails', 'id' => $dashboard->id));
                }
            }
            $view = new HomePageView($this, new DashboardTitleBarAndEditView($this->getId(), $this->getModule()->getId(), $dashboard));
            echo $view->render();
        }

        /**
         * Removes dashboard and related portlets
         *
         */
        public function actionDeleteDashboard()
        {
            $id = intval($_GET['dashboardId']);
            $dashboard = Dashboard::getById($id);
            ControllerSecurityUtil::resolveAccessCanCurrentUserDeleteModel($dashboard);
            if ($dashboard->isDefault)
            {
                //todo: make a specific exception or view for this situation.
                throw new NotSupportedException();
            }
            $portlets = Portlet::getByLayoutIdAndUserSortedById('HomeDashboard' . $id, Yii::app()->user->userModel->id);
            foreach ($portlets as $portlet)
            {
                $portlet->delete();
                unset($portlet);
            }
            $dashboard->delete();
            unset($dashboard);
            $this->redirect(array('default/index'));
        }
    }
?>
