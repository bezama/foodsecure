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
     * Simple view that renders the user's action toolbar.
     * This view is designed to be used as part of the user's
     * security view.
     * @see UserTitleBarAndSecurityDetailsView
     */
    class UserSecurityDetailsView extends MetadataView
    {
        protected $controllerId;

        protected $moduleId;

        public function __construct($controllerId, $moduleId, $modelId)
        {
            assert('$controllerId != null');
            assert('$moduleId     != null');
            assert('$modelId      != null');
            $this->controllerId   = $controllerId;
            $this->moduleId       = $moduleId;
            $this->modelId        = $modelId;
        }

        /**
         * Renders content for a view including a toolbar.
         * @return A string containing the view's content.
         */
        protected function renderContent()
        {
            $content = '<div class="view-toolbar">';
            $content .= $this->renderActionElementBar(false);
            $content .= '</div>';
            return $content;
        }

        public static function getDefaultMetadata()
        {
            $metadata = array(
                'global' => array(
                    'toolbar' => array(
                        'elements' => array(
                            array('type' => 'ListLink',
                                  'label' => "eval:Yii::t('Default', 'Return to List')"),
                            array('type' => 'DetailsLink'),
                            array('type' => 'EditLink'),
                            array('type' => 'AuditEventsModalListLink'),
                            array('type' => 'ChangePasswordLink'),
                            array('type' => 'UserConfigurationEditLink'),
                        ),
                    ),
                ),
            );
            return $metadata;
        }
    }
?>