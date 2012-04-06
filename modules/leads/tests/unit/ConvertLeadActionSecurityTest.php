<?php


    class ConvertLeadActionSecurityTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            fosaDatabaseCompatibilityUtil::dropStoredFunctionsAndProcedures();
            SecurityTestHelper::createSuperAdmin();
            Yii::app()->user->userModel = User::getByUsername('super');
            SecurityTestHelper::createUsers();
        }

        public function testCanCurrentUserPerformAction()
        {
            Yii::app()->user->userModel = User::getByUsername('billy');
            $leadForBilly = LeadTestHelper::createLeadbyNameForOwner("billy's lead", User::getByUsername('billy'));
            $betty = User::getByUsername('betty');
            Yii::app()->user->userModel = $betty;
            $leadForBetty = LeadTestHelper::createLeadbyNameForOwner("betty's lead", User::getByUsername('betty'));
            $betty->setRight('LeadsModule', LeadsModule::RIGHT_ACCESS_LEADS, Right::ALLOW);
            $saved = $betty->save();
            $this->assertTrue($saved);

            //make sure betty doesnt have write on billy's lead
            $this->assertEquals(Permission::NONE, $leadForBilly->getEffectivePermissions      ($betty));
            //make sure betty doesnt have convert lead right already
            $this->assertEquals(Right::DENY, $betty->getEffectiveRight('LeadsModule', LeadsModule::RIGHT_CONVERT_LEADS));

            //test Betty has no right to convert leads
            $actionSecurity = ActionSecurityFactory::createActionSecurityFromActionType('ConvertLead', $leadForBilly, $betty);
            $this->assertFalse ($actionSecurity->canUserPerformAction());

            //test Betty has right to convert leads but cant write the lead she doesn't own
            $betty->setRight   ('LeadsModule', LeadsModule::RIGHT_CONVERT_LEADS, Right::ALLOW);
            $this->assertTrue($betty->save());
            $actionSecurity = ActionSecurityFactory::createActionSecurityFromActionType('ConvertLead', $leadForBilly, $betty);
            $this->assertFalse ($actionSecurity->canUserPerformAction());

            //test Betty has right to convert and to write a lead she owns.
            $actionSecurity = ActionSecurityFactory::createActionSecurityFromActionType('ConvertLead', $leadForBetty, $betty);
            $this->assertTrue ($actionSecurity->canUserPerformAction());
        }
    }
?>