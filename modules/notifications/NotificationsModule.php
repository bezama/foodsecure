<?php

    class NotificationsModule extends Module
    {
        public function getDependencies()
        {
           return array('fosa');
        }

        public function getRootModelNames()
        {
            return array('Notification', 'NotificationMessage');
        }
    }
?>
