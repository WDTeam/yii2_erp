<?php
namespace api\models;

use \yii\base\Model;

class PayParam extends Model
{
    public $pay_money;
    public $customer_id;
    public $channel_id;
    public $partner;
    public $order_id;

    public function rules()
    {
        return [
            [['pay_money','customer_id','channel_id','partner','order_id'],'required'],
        ];
    }

    /**
     * 场景验证
     * @param integer $pay_money 支付金额
     * @param integer $customer_id 消费者ID
     * @param integer $pay_source 来源ID
     * @param integer $pay_source_name 来源名称
     * @param char $order_id 订单ID
     * @param char $partner 第三方合作号
     */
    public function scenarios()
    {
        return[
            //在线充值
            'pay'       =>['pay_money','customer_id','channel_id','partner'],
            //在线支付
            'online_pay'=>['pay_money','customer_id','channel_id','partner','order_id'],
        ];
    }

    public static function tableName()
    {
        return '{{%general_pay}}';
    }
}