<?php
namespace api\controllers;
/**
 * 自动派单
 * @author colee
 *
 */
class AutoOrderController extends \api\components\Controller
{
    /**
     *
     * @api {POST} /ivr/send-sms 自动派单专用发短消息
     * @apiName autoOrder Sendsms
     * @apiGroup SMS
     *
     * @apiParam {Number} telephone 电话
     * @apiParam {Mixed} message 发送消息
     *
     * @apiSuccess {String} code 返回码 1成功，0失败.
     * @apiSuccess {Array} msg 返回消息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "result": "1",
     *       "msg": "ok",
     *       "tel": "15011152243",
     *     }
     *
     */
    public function actionSendSms()
    {
        $data = \Yii::$app->request->post();
        $res = \Yii::$app->sms->send($data['mobiles'], $data['message']);
        if($res){
            return $this->send($res,'发送成功',1);
        }else{
            return $this->send($res,'发送失败',0);
        }
    }
    /**
     *
     * @api {POST} /ivr/send-ivr 自动派单专用IVR
     * @apiName autoOrderIVR
     * @apiGroup SMS
     *
     * @apiParam {Number} telephone 电话
     * @apiParam {Mixed} message 发送消息
     *
     * @apiSuccess {String} code 返回码 1成功，0失败.
     * @apiSuccess {Array} msg 返回消息.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "result": "1",
     *       "msg": "ok",
     *       "tel": "15011152243",
     *     }
     *
     */
    public function actionSendIvr()
    {
        $data = \Yii::$app->request->post();
//         \Yii::$app->ivr->send('手机号','订单ID', '订单消息文本');
        $res = \Yii::$app->ivr->send($data['mobile'], $data['order_id'], $data['message']);
        if($res){
            return $this->send($res,'发送成功',1);
        }else{
            return $this->send($res,'发送失败',0);
        }
    }
}
