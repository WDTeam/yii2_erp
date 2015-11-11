<?php

namespace boss\models\payment;

use core\models\operation\OperationPayChannel;
use core\models\operation\OperationOrderChannel;

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

    /**
     * 获取支付名称
     * @param $id
     * @return mixed
     */
    public function getPayChannelName($id)
    {
        return OperationPayChannel::get_post_name($id);
    }

    /**
     * 支付渠道列表
     * @return array
     */
    public static function getPayChannelList()
    {
        return OperationPayChannel::getpaychannellist('all');
    }

    /**
     * 获取订单渠道名称
     * @param $id
     * @return mixed
     */
    public function getOrderChannelName($id)
    {
        return OperationOrderChannel::get_post_name($id);
    }

    /**
     * 订单渠道列表
     * @return array
     */
    public static function getOrderChannelList()
    {
        return OperationOrderChannel::getorderchannellist('all');
    }

}
