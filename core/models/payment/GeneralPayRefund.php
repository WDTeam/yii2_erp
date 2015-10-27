<?php

namespace core\models\payment;
use Yii;

class GeneralPayRefund extends \common\models\payment\GeneralPayRefund
{
    /**
     * 订单退款
     * @param $order_id
     * @param $customer_id
     */
    public function refund($order_id,$customer_id)
    {
        $model = new GeneralPayRefund();
        return $model->call_pay_refund($order_id,$customer_id);
    }

}
