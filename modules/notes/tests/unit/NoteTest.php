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

    class NoteTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            AccountTestHelper::createAccountByNameForOwner('anAccount', $super);
        }

        public function testCreateAndGetNoteById()
        {
            Yii::app()->user->userModel = User::getByUsername('super');

            $fileModel    = fosaTestHelper::createFileModel();
            $accounts = Account::getByName('anAccount');

            $user                     = UserTestHelper::createBasicUser('Billy');
            $occurredOnStamp          = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
            $note                     = new Note();
            $note->owner              = $user;
            $note->occurredOnDateTime = $occurredOnStamp;
            $note->description       = 'myNote';
            $note->activityItems->add($accounts[0]);
            $note->files->add($fileModel);
            $this->assertTrue($note->save());
            $id = $note->id;
            unset($note);
            $note = note::getById($id);
            $this->assertEquals($occurredOnStamp,      $note->occurredOnDateTime);
            $this->assertEquals('myNote',              $note->description);
            $this->assertEquals($user,                 $note->owner);
            $this->assertEquals(1, $note->activityItems->count());
            $this->assertEquals($accounts[0], $note->activityItems->offsetGet(0));
            $this->assertEquals(1, $note->files->count());
            $this->assertEquals($fileModel, $note->files->offsetGet(0));
        }

        /**
         * @depends testCreateAndGetNoteById
         */
        public function testGetLabel()
        {
            Yii::app()->user->userModel = User::getByUsername('super');
            $notes = Note::getByName('myNote');
            $this->assertEquals(1, count($notes));
            $this->assertEquals('Note',   $notes[0]::getModelLabelByTypeAndLanguage('Singular'));
            $this->assertEquals('Notes',  $notes[0]::getModelLabelByTypeAndLanguage('Plural'));
        }

        /**
         * @depends testGetLabel
         */
        public function testGetNotesByNameForNonExistentName()
        {
            Yii::app()->user->userModel = User::getByUsername('super');
            $notes = Note::getByName('Test Note 69');
            $this->assertEquals(0, count($notes));
        }

        /**
         * @depends testCreateAndGetNoteById
         */
        public function testUpdateNoteFromForm()
        {
            Yii::app()->user->userModel = User::getByUsername('super');

            $user = User::getByUsername('billy');
            $notes = Note::getByName('myNote');
            $note = $notes[0];
            $this->assertEquals($note->description, 'myNote');
            $postData = array(
                'owner' => array(
                    'id' => $user->id,
                ),
                'description' => 'New Name',
                'occurredOnDateTime' => '',
            );
            $sanitizedPostData = PostUtil::sanitizePostByDesignerTypeForSavingModel($note, $postData);
            $note->setAttributes($sanitizedPostData);
            $this->assertTrue($note->save());
            $id = $note->id;
            unset($note);
            $note = Note::getById($id);
            $this->assertEquals('New Name', $note->description);
            $this->assertEquals(null,       $note->occurredOnDateTime);

            //create new note from scratch where the DateTime attributes are not populated. It should let you save.
            $note = new Note();
            $postData = array(
                'owner' => array(
                    'id' => $user->id,
                ),
                'description' => 'Lamazing',
                'occurredOnDateTime' => '',
            );
            $sanitizedPostData = PostUtil::sanitizePostByDesignerTypeForSavingModel($note, $postData);
            $note->setAttributes($sanitizedPostData);
            $this->assertTrue($note->save());
            $id = $note->id;
            unset($note);
            $note = Note::getById($id);
            $this->assertEquals('Lamazing', $note->description);
            $this->assertEquals(null, $note->occurredOnDateTime); //will default to NOW
        }

        /**
         * @depends testUpdateNoteFromForm
         */
        public function testDeleteNote()
        {
            Yii::app()->user->userModel = User::getByUsername('super');
            $notes = Note::getAll();
            $this->assertEquals(2, count($notes));
            $notes[0]->delete();
            $notes = Note::getAll();
            $this->assertEquals(1, count($notes));
        }

        /**
         * @depends testDeleteNote
         */
        public function testAutomatedOccurredOnDateTimeAndLatestDateTimeChanges()
        {
            Yii::app()->user->userModel = User::getByUsername('super');

            //Creating a new note, the occuredOnDateTime and latestDateTime should default to now.
            $note = new Note();
            $note->description = 'aTest';
            $nowStamp = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
            $this->assertTrue($note->save());
            $this->assertEquals($nowStamp, $note->occurredOnDateTime);
            $this->assertEquals($nowStamp, $note->latestDateTime);

            //Modify a note. Do not change the occurredOnDateTime, the latestDateTime should not change.
            $note = Note::getById($note->id);
            $note->description = 'bTest';
            $this->assertTrue($note->save());
            $this->assertEquals($nowStamp, $note->latestDateTime);

            //Modify a note. Change the occurredOnDateTime and the latestDateTime will change.
            $note = Note::getById($note->id);
            $newStamp = DateTimeUtil::convertTimestampToDbFormatDateTime(time() + 1);
            $this->assertNotEquals($nowStamp, $newStamp);
            $this->assertEquals($nowStamp, $note->occurredOnDateTime);
            $note->occurredOnDateTime = $newStamp;
            $this->assertTrue($note->save());
            $this->assertEquals($newStamp, $note->occurredOnDateTime);
            $this->assertEquals($newStamp, $note->latestDateTime);
        }

        /**
         * @depends testAutomatedOccurredOnDateTimeAndLatestDateTimeChanges
         */
        public function testNobodyCanReadWriteDeleteAndStrValOfNoteFunctionsCorrectly()
        {
            Yii::app()->user->userModel = User::getByUsername('super');

            $fileModel    = fosaTestHelper::createFileModel();
            $accounts     = Account::getByName('anAccount');

            //create a nobody user
            $nobody                   = UserTestHelper::createBasicUser('nobody');

            //create a note whoes owner is super
            $occurredOnStamp          = DateTimeUtil::convertTimestampToDbFormatDateTime(time());
            $note                     = new Note();
            $note->owner              = User::getByUsername('super');
            $note->occurredOnDateTime = $occurredOnStamp;
            $note->description        = 'myNote';
            $note->activityItems->add($accounts[0]);
            $note->files->add($fileModel);
            $this->assertTrue($note->save());

            //add nobody permission to read and write the note
            $note->addPermissions($nobody, Permission::READ_WRITE_CHANGE_PERMISSIONS);
            $this->assertTrue($note->save());

            //revoke the permission from the nobody user to access the note
            $note->removePermissions($nobody, Permission::READ_WRITE_CHANGE_PERMISSIONS);
            $this->assertTrue($note->save());

            //add nobody permission to read, write and delete the note
            $note->addPermissions($nobody, Permission::READ_WRITE_DELETE);
            $this->assertTrue($note->save());

            //now acces to the notes read by nobody should not fail
            Yii::app()->user->userModel = $nobody;
            $this->assertEquals($note->description, strval($note));
        }

        /**
         * @depends testNobodyCanReadWriteDeleteAndStrValOfNoteFunctionsCorrectly
         */
        public function testAUserCanDeleteANoteNotOwnedButHasExplicitDeletePermission()
        {
            //Create superAccount owned by user super.
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            $superAccount = AccountTestHelper::createAccountByNameForOwner('AccountTest', $super);

            //create a nobody user
            $nobody                   = User::getByUsername('nobody');

            //create note for an superAccount using the super user
            $note = NoteTestHelper::createNoteWithOwnerAndRelatedAccount('noteCreatedBySuper', $super, $superAccount);

            //give nobody access to both details, edit and delete view in order to check the delete of a note
            Yii::app()->user->userModel = User::getByUsername('super');
            $nobody->forget();
            $nobody = User::getByUsername('nobody');
            $note->addPermissions($nobody, Permission::READ_WRITE_DELETE);
            $this->assertTrue($note->save());
            Yii::app()->user->userModel = User::getByUsername('nobody');
            $noteId = $note->id;
            $note->forget();
            $note = Note::getById($noteId);
            $note->delete();
        }

        public function testGetModelClassNames()
        {
            $modelClassNames = NotesModule::getModelClassNames();
            $this->assertEquals(1, count($modelClassNames));
            $this->assertEquals('Note', $modelClassNames[0]);
        }
    }
?>
