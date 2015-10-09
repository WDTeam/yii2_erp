<?php
namespace api\components;

class Controller extends \yii\rest\Controller
{
    public $appVersion = 1.0;
    public $appKey = 0;
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //just provide the json format
        unset($behaviors['contentNegotiator']);
        return $behaviors;
    }

    public function beforeAction($action)
    {
//        return true;
        $headers = \Yii::$app->request->headers;
        $this->appVersion =  $headers->get('version');
        $this->appKey =  $headers->get('appkey');
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        $result = [
            'success' => isset($result['success']) ? $result['success'] : true,
            'data' => $result,
            'errorCode' => isset($result['errorCode']) ? $result['errorCode'] : '100000',
            'errorMsg' => isset($result['errorMsg']) ? $result['errorMsg'] : "",
            'trace' => null
        ];
        return $result;
    }

}