<?php


    /**
     * Api test controller, used to test actions related to ApiTestModelItem2
     */
    class ApiTestModelItem2ApiController extends fosaModuleApiController
    {
        public function actionCustomGet()
        {
            $params = Yii::app()->apiHelper->getRequestParams();
            if (!isset($params['id']))
            {
                $message = Yii::t('Default', 'The id specified was invalid.');
                throw new ApiException($message);
            }
            $result    =  $this->processRead((int)$params['id']);
            Yii::app()->apiHelper->sendResponse($result);
        }

        public function actionCustomList()
        {
            $params = Yii::app()->apiHelper->getRequestParams();
            $result    =  $this->processList($params);
            Yii::app()->apiHelper->sendResponse($result);
        }

        public function actionCustomPost()
        {
            $params = Yii::app()->apiHelper->getRequestParams();
            if (!isset($params['data']))
            {
                $message = Yii::t('Default', 'Please provide data.');
                throw new ApiException($message);
            }
            $result    =  $this->processCreate($params['data']);
            Yii::app()->apiHelper->sendResponse($result);
        }

        public function actionCustomUpdate()
        {
            $params = Yii::app()->apiHelper->getRequestParams();
            if (!isset($params['id']))
            {
                $message = Yii::t('Default', 'The id specified was invalid.');
                throw new ApiException($message);
            }
            $result    =  $this->processUpdate((int)$params['id'], $params['data']);
            Yii::app()->apiHelper->sendResponse($result);
        }

        public function actionCustomDelete()
        {
            $params = Yii::app()->apiHelper->getRequestParams();
            if (!isset($params['id']))
            {
                $message = Yii::t('Default', 'The id specified was invalid.');
                throw new ApiException($message);
            }
            $result    =  $this->processDelete((int)$params['id']);
            Yii::app()->apiHelper->sendResponse($result);
        }

        protected function getModelName()
        {
            return 'ApiTestModelItem2';
        }

        protected function getSearchFormClassName()
        {
            return 'ApiTestModelItem2SearchForm';
        }
    }
?>
