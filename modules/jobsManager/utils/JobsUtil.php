<?php


    /**
     * A helper class for working with Jobs.
     */
    class JobsUtil
    {
        /**
         * Given a 'type' of job, return the stringified content of that Job. If the job type does not
         * exist for some reason, then just return it as '(Unnamed').  This method always returns
         * translated content.
         * @param string $type
         */
        public static function resolveStringContentByType($type)
        {
            assert('$type != null && is_string($type)');
            $jobClassName = $type . 'Job';
            if (@class_exists($jobClassName))
            {
                return $jobClassName::getDisplayName();
            }
            else
            {
                return Yii::t('Default', '(Unnamed)');
            }
        }
    }
?>