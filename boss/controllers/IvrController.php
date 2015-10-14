<?php
namespace boss\controllers;

use yii\web\Controller;
use common\components\Ivr;
class IvrController extends Controller
{
    /**
     * IVR回调
     */
    public function actionCallback()
    {
        $params = \Yii::$app->request->getQueryParams();
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
        $I = new Ivr();
        $res = $I->send('15110249233', 'A'.time(),'E家洁，您身边的家政专家');
        var_dump($res);
    }
}