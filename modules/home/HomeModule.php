<?php


    class HomeModule extends SecurableModule
    {
        const RIGHT_CREATE_DASHBOARDS = 'Create Dashboards';
        const RIGHT_DELETE_DASHBOARDS = 'Delete Dashboards';
        const RIGHT_ACCESS_DASHBOARDS = 'Access Dashboards';

        public function getDependencies()
        {
            return array(
                'fosa',
            );
        }

        public function getRootModelNames()
        {
            return array('Dashboard');
        }

        public static function getTabMenuItems($user = null)
        {
            $dashboards = null;
            $tabMenuItems = parent::getTabMenuItems();
            if ($user instanceof User && $user->id > 0)
            {
                $dashboards = Dashboard::getRowsByUserId($user->id);
            }
            if (!empty($dashboards))
            {
                $foundSavedDefaultDashboard = false;
                 $tabMenuItems = array(
                    array(
                        'label' => 'Home',
                        'url'   => array('/home/default'),
                    ),
                );
                foreach ($dashboards as $dashboard)
                {
                    if ($dashboard['layoutId'] == 1)
                    {
                        $foundSavedDefaultDashboard = true;
                    }
                    $menuItems[] = array(
                        'label' => $dashboard['name'],
                        'url'   => array('/home/default/dashboardDetails&id=' . $dashboard['id']),
                        'right' => self::RIGHT_ACCESS_DASHBOARDS
                    );
                }
                if (!$foundSavedDefaultDashboard)
                {
                    array_unshift($menuItems, array(
                            'label' => 'Dashboard',
                            'url'   => array('/home/default/dashboardDetails'),
                            'right' => self::RIGHT_ACCESS_DASHBOARDS
                        )
                    );
                }
                    $menuItems[] = array(
                        'label' => 'Create Dashboard',
                        'url'   => array('/home/default/createDashboard'),
                        'right' => self::RIGHT_CREATE_DASHBOARDS
                    );
                $tabMenuItems[0]['items'] = $menuItems;
            }
            return $tabMenuItems;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array();
            $metadata['global'] = array(
                'tabMenuItems' => array(
                    array(
                        'label' => 'Home',
                        'url'   => array('/home/default'),
                        'items' => array(
                            array(
                                'label' => 'Dashboard',
                                'url'   => array('/home/default/dashboardDetails'),
                                'right' => self::RIGHT_ACCESS_DASHBOARDS
                            ),
                            array(
                                'label' => 'Create Dashboard',
                                'url'   => array('/home/default/createDashboard'),
                                'right' => self::RIGHT_CREATE_DASHBOARDS
                            ),
                        ),
                    ),

                ),
            );
            return $metadata;
        }

        protected static function getSingularModuleLabel()
        {
            return 'Home';
        }

        public static function getDeleteRight()
        {
            return self::RIGHT_DELETE_DASHBOARDS;
        }
    }
?>
