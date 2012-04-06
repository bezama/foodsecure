<?php


    /**
     * A helper class for rendering view information from a JobLog model.
     */
    class JobLogViewUtil
    {
        public static function renderStatusAndMessageListContent(JobLog $jobLog)
        {
            if ($jobLog->status == JobLog::STATUS_COMPLETE_WITH_ERROR)
            {
                $content     = '<span id="active-nonmonitor-job-tooltip-' .
                               $jobLog->id . '" class="tooltip" title="' . CHtml::encode($jobLog->message) . '">';
                $content    .= Yii::t('Default', 'Completed with Errors') . '</span>';
                Yii::import('application.extensions.qtip.QTip');
                $options     = array('content' =>
                                        array('title' =>
                                            array('text'   => Yii::t('Default', 'Error Log'),
                                                  'button' => Yii::t('Default', 'Close'))
                                        ),
                                     'adjust' =>
                                        array('screen' => true),
                                     'position' =>
                                        array('corner' =>
                                            array('target' => 'bottomRight',
                                                  'tooltip' => 'topRight')),
                                     'style'  => array('width' => array('max' => 600)),
                                     'api' => array('beforeHide' => 'js:function (event, api)
                                                                     { if (event.originalEvent.type !== "click")
                                                                     { return false;}}')
                               ); // Not Coding Standard
                $qtip        = new QTip();
                $qtip->addQTip("#active-nonmonitor-job-tooltip-" . $jobLog->id, $options);
                return $content;
            }
            elseif ($jobLog->status == JobLog::STATUS_COMPLETE_WITHOUT_ERROR)
            {
                return Yii::t('Default', 'Completed');
            }
            else
            {
                throw new NotSupportedException();
            }
        }
    }
?>