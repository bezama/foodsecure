<?php


    /**
     * Base class to test API functions.
     */
    class ApiBaseTest extends BaseTest
    {
        protected $serverUrl = '';
        protected $freeze = false;

        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            $super = SecurityTestHelper::createSuperAdmin();
        }

        public function setUp()
        {
            parent::setUp();
            if (strlen(Yii::app()->params['testApiUrl']) > 0)
            {
                $this->serverUrl = Yii::app()->params['testApiUrl'];
            }
            $freeze = false;
            if (RedBeanDatabase::isFrozen())
            {
                RedBeanDatabase::unfreeze();
                $freeze = true;
            }
            $this->freeze = $freeze;
            fosaModule::setfosaToken(1111111111);
        }

        public function teardown()
        {
            if ($this->freeze)
            {
                RedBeanDatabase::freeze();
            }
            parent::teardown();
        }

        public function testApiServerUrl()
        {
            $this->assertTrue(strlen($this->serverUrl) > 0);
        }
    }
?>