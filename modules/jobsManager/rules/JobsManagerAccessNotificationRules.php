<?php


    /**
     * Base class for notifications that are notifying users who can access the job manager, where
     * the notification would be sent to all of those users.
     */
    abstract class JobsManagerAccessNotificationRules extends NotificationRules
    {
        /**
         * Any user who has access to the scheduler module is added to receive a
         * notification.
         */
        protected function loadUsers()
        {
            foreach (User::getAll() as $user)
            {
                if ($user->getEffectiveRight('JobsManagerModule', JobsManagerModule::RIGHT_ACCESS_JOBSMANAGER) ==
                    Right::ALLOW)
                {
                    $this->addUser($user);
                }
            }
        }
    }
?>