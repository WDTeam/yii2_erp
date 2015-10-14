<?php
namespace boss\controllers;

use yii\web\Controller;
class IvrController extends Controller
{
    public $enableCsrfValidation = false;
    /**
     * IVR回调,POST
     */
    public function actionCallback()
    {
        $params = \Yii::$app->request->post();
        $text = json_encode($params);
        $sendres = \Yii::$app->mailer->compose()
        ->setFrom('service@corp.1jiajie.com')
        ->setTo('lidenggao@1jiajie.com')
        ->setSubject('ivr callback ')
        ->setTextBody($text)
        ->send();
        return '请求的数据：'.$text;
    }
    public function actionTest()
    {
        $res = \Yii::$app->ivr->send('15110249233', 'A'.time(),'E家洁，您身边的家政专家');
        var_dump($res);
        return $res;
    }
}