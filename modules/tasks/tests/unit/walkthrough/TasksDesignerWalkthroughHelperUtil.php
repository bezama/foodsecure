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
     * Helper functions to assist with testing designer walkthroughs specifically for task layouts.
     */
    class TasksDesignerWalkthroughHelperUtil
    {
        public static function getTaskEditAndDetailsViewLayoutWithAllCustomFieldsPlaced()
        {
            return array(
                    'panels' => array(
                        array(
                            'title' => 'Panel Title',
                            'panelDetailViewOnly' => 1,
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'name',
                                        ),
                                        array(
                                            'element' => 'dueDateTime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'owner',
                                        ),
                                        array(
                                            'element' => 'completed', // Not Coding Standard
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'completedDateTime',
                                        ),
                                        array(
                                            'element' => 'ActivityItems',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'description',
                                        ),
                                        array(
                                            'element' => 'Null', // Not Coding Standard
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'detailViewOnly' => true,
                                            'element' => 'DateTimeCreatedUser',
                                        ),
                                        array(
                                            'detailViewOnly' => true,
                                            'element' => 'DateTimeModifiedUser',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'checkbox',
                                        ),
                                        array(
                                            'element' => 'currency',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'date',
                                        ),
                                        array(
                                            'element' => 'datetime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'decimal',
                                        ),
                                        array(
                                            'element' => 'picklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'integer',
                                        ),
                                        array(
                                            'element' => 'multiselect',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'tagcloud',
                                        ),
                                        array(
                                            'element' => 'calculatednumber',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'dropdowndependency',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'phone',
                                        ),
                                        array(
                                            'element' => 'radio',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'text',
                                        ),
                                        array(
                                            'element' => 'textarea',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'url',
                                        ),
                                        array(
                                            'element' => 'Null', // Not Coding Standard
                                        ),
                                    )
                                ),
                            ),
                        ),
                    ),
            );
        }

        /**
         * Can be use for relatedListView.
         */
        public static function getTasksRelatedListViewLayoutWithAllStandardAndCustomFieldsPlaced()
        {
            return array(
                    'panels' => array(
                        array(
                            'rows' => array(
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'name',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'owner',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'dueDateTime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'completedDateTime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'completed',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'description',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'createdDateTime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'modifiedDateTime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'createdByUser',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'modifiedByUser',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'checkbox',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'currency',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'date',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'datetime',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'decimal',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'picklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'integer',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'multiselect',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'tagcloud',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'calculatednumber',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'countrypicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'statepicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'citypicklist',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'phone',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'radio',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'text',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'textarea',
                                        ),
                                    )
                                ),
                                array('cells' =>
                                    array(
                                        array(
                                            'element' => 'url',
                                        ),
                                    )
                                ),
                            ),
                        ),
                    ),
            );
        }
    }
?>