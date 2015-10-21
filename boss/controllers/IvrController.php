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
        $data = \Yii::$app->ivr->cb_data;
        $data['orderId'];
        $data['press'];
        $data['telephone'];
        if(isset($data['postType']) && $data['postType']==1){
            // code=1表示接单成功
            return json_encode([
                'code'=>1
            ]);
        }
    }
    
    public function actionTest()
    {
        $res = \Yii::$app->ivr->send('15110249233', 'A1444808735', '洗衣');
        var_dump($res);
    }
    
    public function actionJpush()
    {
        $res = \Yii::$app->jpush->push(['wworker_uuu_ttt2', 'worker_ldg_test', 'wworker_uuu_ttt'], 'test', ['test'=>'自定义数据']);
        var_dump($res);
        if(isset($res->msg_id)){
            echo '<br/>'.'<br/>'.'<br/>';
            var_dump(\Yii::$app->jpush->getReport($res->msg_id));
        }
    }
}