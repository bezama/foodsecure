<?php
    /*********************************************************************************
     * fosa is a customer relationship management program developed by
     * fosa, Inc. Copyright (C) 2012 fosa Inc.
     *
     * fosa is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY fosa, fosa DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * fosa is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
     * details.
     *
     * You should have received a copy of the GNU General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact fosa, Inc. with a mailing address at 113 McHenry Road Suite 207,
     * Buffalo Grove, IL 60089, USA. or at email address contact@fosa.com.
     ********************************************************************************/

    /**
     * Helper class to work with notifications.
     */
    class NotificationsUtil
    {
        protected static function getEmailSubject()
        {
            return Yii::t('Default', 'You have a new notification');
        }

        /**
         * Given a NotificationMessage and a NotificationRule submit and process a notification
         * to one or more users.
         * @param NotificationMessage $message
         * @param NotificationRules $rules
         */
        public static function submit(NotificationMessage $message, NotificationRules $rules)
        {
            $users = $rules->getUsers();
            if (count($users) == 0)
            {
                throw new NotSupportedException();
            }
            static::processNotification($message,
                                        $rules->getType(),
                                        $users,
                                        $rules->allowDuplicates(),
                                        $rules->isCritical());
        }

        protected static function processNotification(NotificationMessage $message, $type, $users,
                                                      $allowDuplicates, $isCritical)
        {
            assert('is_string($type) && $type != ""');
            assert('is_array($users) && count($users) > 0');
            assert('is_bool($allowDuplicates)');
            assert('is_bool($isCritical)');
            $notifications = array();
            foreach ($users as $user)
            {
                //todo: !!!process duplication check
                if ($allowDuplicates || Notification::getUnreadCountByTypeAndUser($type, $user) == 0)
                {
                    $notification                      = new Notification();
                    $notification->owner               = $user;
                    $notification->type                = $type;
                    $notification->isRead              = false;
                    $notification->notificationMessage = $message;
                    $saved                             = $notification->save();
                    if (!$saved)
                    {
                        throw new NotSupportedException();
                    }
                    $notifications[] = $notification;
                }
            }
            if (static::resolveShouldSendEmailIfCritical() && $isCritical)
            {
                foreach ($notifications as $notification)
                {
                    static::sendEmail($notification);
                }
            }
        }

        protected static function resolveShouldSendEmailIfCritical()
        {
            return true;
        }

        protected static function sendEmail(Notification $notification)
        {
            if ($notification->owner->primaryEmail->emailAddress !== null)
            {
                $userToSendMessagesFrom    = Yii::app()->emailHelper->getUserToSendNotificationsAs();
                $emailMessage              = new EmailMessage();
                $emailMessage->owner       = Yii::app()->user->userModel;
                $emailMessage->subject     = static::getEmailSubject();
                $emailContent              = new EmailMessageContent();
                $emailContent->textContent = $notification->notificationMessage->textContent;
                $emailContent->htmlContent = $notification->notificationMessage->htmlContent;
                $emailMessage->content     = $emailContent;
                $sender                    = new EmailMessageSender();
                $sender->fromAddress       = Yii::app()->emailHelper->resolveFromAddressByUser($userToSendMessagesFrom);
                $sender->fromName          = strval($userToSendMessagesFrom);
                $emailMessage->sender      = $sender;
                $recipient                 = new EmailMessageRecipient();
                $recipient->toAddress      = $notification->owner->primaryEmail->emailAddress;
                $recipient->toName         = strval($notification->owner);
                $recipient->type           = EmailMessageRecipient::TYPE_TO;
                $recipient->person         = $notification->owner;
                $emailMessage->recipients->add($recipient);
                $box                       = EmailBox::resolveAndGetByName(EmailBox::NOTIFICATIONS_NAME);
                $emailMessage->folder      = EmailFolder::getByBoxAndType($box, EmailFolder::TYPE_DRAFT);
                Yii::app()->emailHelper->sendImmediately($emailMessage);
            }
        }
    }
?>