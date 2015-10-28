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
    public $openid;
    public $customer_name;
    public $customer_mobile;
    public $customer_address;
    public $order_source_url;
    public $page_url;
    public $detail;
    public $return_url;
    public $show_url;

    public function rules()
    {
        return [
            [['pay_money','customer_id','channel_id','partner','order_id','openid','customer_name','customer_mobile','customer_address','order_source_url','page_url','detail'],'required'],
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
            'pay'       =>              ['pay_money','customer_id','channel_id','partner'],
            //在线支付
            'online_pay'=>              ['pay_money','customer_id','channel_id','partner','order_id'],
            //微信在线充值
            'wx_h5_pay' =>              ['pay_money','customer_id','channel_id','partner','openid'],
            //微信在线支付
            'wx_h5_online_pay'=>        ['pay_money','customer_id','channel_id','partner','order_id','openid'],
            //微信在线充值
            'zhidahao_h5_pay' =>        ['pay_money','customer_id','channel_id','partner','customer_name','customer_mobile','customer_address','order_source_url','page_url','detail'],
            //微信在线支付
            'zhidahao_h5_online_pay'=>  ['pay_money','customer_id','channel_id','partner','order_id','customer_name','customer_mobile','customer_address','order_source_url','page_url','detail'],
            //支付宝WEB
            'alipay_web_pay'    =>      ['pay_money','customer_id','channel_id','partner','return_url','show_url'],
            //支付宝WEB
            'alipay_web_online_pay' =>  ['pay_money','customer_id','channel_id','partner','order_id','return_url','show_url'],

        ];
    }

    public static function tableName()
    {
        return '{{%general_pay}}';
    }
}