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
     * This validator can be used by both a User model as well as a CFormModel like in User import for example.
     * This validator assumes that if the model is not a User model, then the respected minimum length should
     * be based on the Everyone group policy.
     * See the yii documentation.
     */
    class UsernameLengthValidator extends CValidator
    {
        /**
         * See the yii documentation.
         */
        protected function validateAttribute($model, $attributeName)
        {
            if ($model instanceof User)
            {
                $minLength = $model->getEffectivePolicy('UsersModule', UsersModule::POLICY_MINIMUM_USERNAME_LENGTH);
            }
            elseif ($model instanceof CFormModel)
            {
                $group     = Group::getByName(Group::EVERYONE_GROUP_NAME);
                $minLength = $group->getEffectivePolicy('UsersModule', UsersModule::POLICY_MINIMUM_USERNAME_LENGTH);
            }
            else
            {
                throw new NotSupportedException();
            }
            //$model->$attributeName != null &&
            if ($model->$attributeName != null && strlen($model->$attributeName) < $minLength)
            {
                $model->addError($attributeName,
                                 Yii::t('Default', 'The username is too short. Minimum length is {minimumLength}.',
                                 array('{minimumLength}' => $minLength)));
            }
        }
    }
?>
