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
        $data = \Yii::$app->request->post();
        $text = \Yii::$app->ivr->callback($data);
        if($data['postType']==1){
            return json_encode([
                'code'=>1
            ]);
        }else{
            return '请求的数据：'.$text;
        }
    }
    public function actionTest()
    {
        $res = \Yii::$app->ivr->send('15110249233', 'A'.time(),'E家洁，您身边的家政专家');
        var_dump($res);
        return $res;
    }
}