<?php

namespace core\components;

use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\filters\RateLimiter;
use yii;

class ActiveController extends \yii\rest\ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        //just provide the json format
        unset($behaviors['contentNegotiator']);
        return $behaviors;
    }

    public function beforeAction($action)
    {
        $headers = Yii::$app->request->headers;
        if ($headers->has('appkey')) {
            $key = $headers->get('appkey');
            $time = $headers->get('time');
            $sign = $headers->get('sign');
            $apiname = Yii::$app->request->pathInfo;
            $appkeys = Yii::$app->params['appkeys'];
            if (in_array($key,$appkeys) && $sign==md5($apiname.'-'.$key.'-'.$time) || ($key=='ejiajie_docs_v1' && YII_ENV_DEV)) {
                if(Yii::$app->request->isPost){
                    $msg = [
                        'appkey'=>$headers->get('appkey'),
                        'params'=>Yii::$app->request->queryParams+Yii::$app->request->post(),
                        'action'=>Yii::$app->request->pathInfo,
                    ];
                    Yii::info(json_encode($msg),'core-service-post');
                }
                return parent::beforeAction($action);
            } else {
                throw new ForbiddenHttpException('接口认证失败', 10031);
            }
        } else {
            throw new ForbiddenHttpException('缺少认证参数', 10006);
        }
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        $result = [
            'success' => Yii::$app->response->statusCode==200?true:false,
            'data' => $result,
            'errorCode' => isset($result['errorCode']) ? $result['errorCode'] : (Yii::$app->response->statusCode==200?1:0),
            'errorMsg' => Yii::$app->response->statusText,
            'status' => Yii::$app->response->statusCode,
        ];
        return $result;
    }
}