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

    class NotesDefaultController extends ActivityModelsDefaultController
    {
        /**
         * Action for saving a new note inline edit form.
         * @param string or array $redirectUrl
         */
        public function actionInlineCreateSave($redirectUrl = null)
        {
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'inline-edit-form')
            {
                $this->actionInlineEditValidate(new Note(), 'Note');
            }
            $this->attemptToSaveModelFromPost(new Note(), $redirectUrl);
        }

        /**
         * Action for saving an existing note inline edit form.
         * @param string or array $redirectUrl
         */
        public function actionInlineEditSave($id, $redirectUrl = null)
        {
            $note = Note::getById((int)$id);
            ControllerSecurityUtil::resolveAccessCanCurrentUserWriteModel($note);
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'inline-edit-form')
            {
                $this->actionInlineEditValidate($note, 'Note');
            }
            $this->attemptToSaveModelFromPost($note, $redirectUrl);
        }

        protected function actionInlineEditValidate($model)
        {
            $readyToUsePostData            = ExplicitReadWriteModelPermissionsUtil::
                                                     removeIfExistsFromPostData($_POST[get_class($model)]);
            $sanitizedPostData             = PostUtil::
                                             sanitizePostByDesignerTypeForSavingModel($model, $readyToUsePostData);
            $sanitizedOwnerPostData        = PostUtil::
                                             sanitizePostDataToJustHavingElementForSavingModel($sanitizedPostData, 'owner');
            $sanitizedPostDataWithoutOwner = PostUtil::removeElementFromPostDataForSavingModel($sanitizedPostData, 'owner');
            $model->setAttributes($sanitizedPostDataWithoutOwner);
            if ($model->validate())
            {
                $modelToStringValue = strval($model);
                if ($sanitizedOwnerPostData != null)
                {
                    $model->setAttributes($sanitizedOwnerPostData);
                }
                if ($model instanceof OwnedSecurableItem)
                {
                    $model->validate(array('owner'));
                }
            }
            $errorData = array();
            foreach ($model->getErrors() as $attribute => $errors)
            {
                    $errorData[CHtml::activeId($model, $attribute)] = $errors;
            }
            echo CJSON::encode($errorData);
            Yii::app()->end(0, false);
        }

        /**
         * Override to handle incoming file upload information.
         * @see ActivitiesModuleController::resolveModelsHasManyRelationsFromPost()
         */
        protected function resolveModelsHasManyRelationsFromPost(& $model)
        {
            assert('$model instanceof Activity');
            parent::resolveModelsHasManyRelationsFromPost($model);
            FileModelUtil::resolveModelsHasManyFilesFromPost($model, 'files', 'filesIds');
        }
    }
?>
