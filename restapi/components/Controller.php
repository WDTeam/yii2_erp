<?php
namespace restapi\components;

use Yii;
use yii\base\Object;
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
     *
     * @param Array|String|Number $data 输出内容
     * @param integer $error_code 错误码
     * @param string $msg 信息
     */
    public function send($ret, $msg = "操作成功", $code = 1, $value = 200, $text = null, $alertMsg = "")
    {
        $value = 200;
        if (is_null($ret)) $ret = new Object();
        $result = [
            'code' => $code,
            'msg' => $msg,
            'ret' => $ret,
            'alertMsg' => $alertMsg
        ];
        $response = Yii::$app->response;
        $response->format = Yii\web\Response::FORMAT_JSON;
        $response->data = $result;

        $response->setStatusCode($value, $text);
        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Access-Control-Allow-Methods", "*");
        $response->headers->set("Access-Control-Allow-Headers", "*");
        return $response;
    }

    public function getUserIdByToken($token)
    {
        $id = Yii::$app->cache->get($token);
        return $id;
    }

}