<?php


    class DashboardTestHelper
    {
        public static function createDashboardByNameForOwner($name, $owner)
        {
            $dashboard               = new Dashboard();
            $dashboard->name         = $name;
            $dashboard->layoutId     = Dashboard::getNextLayoutId();
            $dashboard->owner        = $owner;
            $dashboard->layoutType   = '50,50'; // Not Coding Standard
            $dashboard->isDefault    = false;
            $saved                   = $dashboard->save();
            assert('$saved');
            return $dashboard;
        }
    }
?>