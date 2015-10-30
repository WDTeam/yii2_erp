<?php

namespace boss\models\payment;

use Yii;
use yii\base\ErrorException;
use yii\base\Exception;

class Payment extends \core\models\payment\Payment
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(),[
            'payment_money' => Yii::t('app', '支付金额'),
            'payment_actual_money' => Yii::t('app', '实付金额'),
            'payment_source' => Yii::t('app', '数据来源'),
            'payment_mode' => Yii::t('app', '交易方式'),
            'payment_status' => Yii::t('app', '状态'),
            'payment_transaction_id' => Yii::t('app', '交易流水号'),
            'payment_eo_order_id' => Yii::t('app', '商户订单号'),
            'payment_memo' => Yii::t('app', '备注'),
            'payment_is_coupon' => Yii::t('app', '是否返券'),
            'payment_verify' => Yii::t('app', '支付验证'),
            'created_at' => Yii::t('app', '请求时间'),
            'updated_at' => Yii::t('app', '支付时间'),
        ]);
    }
}