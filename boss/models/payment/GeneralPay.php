<?php

namespace boss\models\payment;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
class GeneralPay extends \core\models\payment\GeneralPay
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
            'general_pay_money' => Yii::t('app', '支付金额'),
            'general_pay_actual_money' => Yii::t('app', '实付金额'),
            'general_pay_source' => Yii::t('app', '数据来源'),
            'general_pay_mode' => Yii::t('app', '交易方式'),
            'general_pay_status' => Yii::t('app', '状态'),
            'general_pay_transaction_id' => Yii::t('app', '交易流水号'),
            'general_pay_eo_order_id' => Yii::t('app', '商户订单号'),
            'general_pay_memo' => Yii::t('app', '备注'),
            'general_pay_is_coupon' => Yii::t('app', '是否返券'),
            'general_pay_verify' => Yii::t('app', '支付验证'),
            'created_at' => Yii::t('app', '请求时间'),
            'updated_at' => Yii::t('app', '支付时间'),
        ]);
    }
}
