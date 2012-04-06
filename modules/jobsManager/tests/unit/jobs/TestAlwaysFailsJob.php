<?php


    /**
     * A job for making unit tests regarding the Job Manager. This test will always fail
     */
    class TestAlwaysFailsJob extends TestJob
    {
        public $causeFailure = true;

        public static function getType()
        {
            return 'TestAlwaysFails';
        }
    }
?>