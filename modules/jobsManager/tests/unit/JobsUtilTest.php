<?php


    class JobsUtilTest extends BaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
        }

        public function testResolveStringContentByType()
        {
            $content = JobsUtil::resolveStringContentByType('Monitor');
            $this->assertEquals('Monitor Job', $content);
            $content = JobsUtil::resolveStringContentByType('NotRealJob');
            $this->assertEquals('(Unnamed)', $content);
        }
    }
?>
