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
        $data = \Yii::$app->ivr->callback(\Yii::$app->request->post());
        var_dump(\Yii::$app->ivr->model);
        if(isset($data['postType']) && $data['postType']==1){
            // code=1表示接单成功
            return json_encode([
                'code'=>1
            ]);
        }
    }
    
    public function actionTest()
    {
        \Yii::$app->ivr->send('15110249233', 'A1444808733', '洗衣');
    }
}