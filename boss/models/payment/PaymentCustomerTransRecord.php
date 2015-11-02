<?php

namespace boss\models\payment;

use Yii;
use yii\base\ErrorException;
use yii\base\Exception;

class PaymentCustomerTransRecord extends \core\models\payment\PaymentCustomerTransRecord
{
    //支付状态
    public static $PAY_STATUS = [
        1=>"成功",
        0=>"失败",
    ];

    //交易方式
    public static $PAY_MODE = [
        1=>"消费",
        2=>"充值",
        3=>"退款",
        4=>"补偿"
    ];

}
