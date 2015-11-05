<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/11/5
 * Time: 15:20
 */

namespace core\models\order;

use Yii;

class OrderMsg extends Model
{
    public static function paymentToCustomer($phone,$begin_time,$service_item,$order_pay_money){
        return Yii::$app->sms->send($phone,"【预约成功】您已成功预约“服务时间”".服务类型."，已支付费用“　”元，订单待指派。如有疑问可致电客服热线4006767636。【e家洁】");
    }
}