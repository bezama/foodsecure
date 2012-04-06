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
     * Class that builds demo notes.
     */
    class NotesDemoDataMaker extends DemoDataMaker
    {
        protected $ratioToLoad = 3;

        public static function getDependencies()
        {
            return array('opportunities');
        }

        public function makeAll(& $demoDataHelper)
        {
            assert('$demoDataHelper instanceof DemoDataHelper');
            assert('$demoDataHelper->isSetRange("User")');
            assert('$demoDataHelper->isSetRange("Opportunity")');

            $notes = array();
            for ($i = 0; $i < $this->resolveQuantityToLoad(); $i++)
            {
                $note           = new Note();
                $opportunity    = $demoDataHelper->getRandomByModelName('Opportunity');
                $note->owner    = $opportunity->owner;
                $note->activityItems->add($opportunity);
                $note->activityItems->add($opportunity->contacts[0]);
                $note->activityItems->add($opportunity->account);
                $this->populateModel($note);
                $saved = $note->save();
                assert('$saved');
                $notes[] = $note->id;
            }
            $demoDataHelper->setRangeByModelName('Note', $notes[0], $notes[count($notes)-1]);
        }

        public function populateModel(& $model)
        {
            assert('$model instanceof Note');
            parent::populateModel($model);
            $taskRandomData            = fosaRandomDataUtil::
                                         getRandomDataByModuleAndModelClassNames('NotesModule', 'Note');
            $description               = RandomDataUtil::getRandomValueFromArray($taskRandomData['descriptions']);
            $occurredOnTimeStamp       = time() - (mt_rand(1, 200) * 60 * 60 * 24);
            $occurredOnDateTime        = DateTimeUtil::convertTimestampToDbFormatDateTime($occurredOnTimeStamp);
            $model->description        = $description;
            $model->occurredOnDateTime = $occurredOnDateTime;
        }
    }
?>
