<?php
namespace boss\controllers;
use yii\web\Controller;
use common\components\Ivr;
class IvrController extends Controller
{
    public function actionCallback()
    {
        $text = json_encode($_GET);
        return \Yii::$app->mailer->compose()
        ->setFrom('service@corp.1jiajie.com')
        ->setTo('lidenggao@1jiajie.com')
        ->setSubject('ivr callback ')
        ->setTextBody($text)
        ->send();
    }
    public function actionTest()
    {
        $I = new Ivr();
        $res = $I->send('15110249233', 'sdf12f123123o','E家洁，您身边的家政装甲');
        var_dump($res);
    }
}