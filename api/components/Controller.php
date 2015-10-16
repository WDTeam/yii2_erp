<?php
namespace api\components;

use Yii;
use yii\web\HttpException;
use yii\base\ExitException;
use yii\base\ErrorException;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;

class Controller extends \yii\rest\Controller
{
    public $version;
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        $this->version = Yii::$app->request->get('version');
        return parent::beforeAction($action);
    }

    /**
     * 输出结果处理
     * @param Array|String|Number $data 输出内容
     * @param integer $error_code 错误码
     * @param string $msg 信息
     */
    public function send($ret, $msg = "操作成功", $code = "ok", $value = 200, $text = null)
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'ret' => $ret
        ];

        $response = Yii::$app->response;
        $response->format = Yii\web\Response::FORMAT_JSON;
        $response->data = $result;
        $response->setStatusCode($value, $text);
        return $response;
    }

    public function getUserIdByToken($token)
    {
        $id = Yii::$app->cache->get($token);
        return $id;
    }

}