<?php

namespace core\models\GeneralPay\GeneralRefund;

use Yii;

/**
 * This is the model class for table "{{%general_pay}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $order_id
 * @property string $general_pay_money
 * @property string $general_pay_actual_money
 * @property integer $general_pay_source
 * @property string $general_pay_source_name
 * @property integer $general_pay_mode
 * @property integer $general_pay_status
 * @property string $general_pay_transaction_id
 * @property string $general_pay_eo_order_id
 * @property string $general_pay_memo
 * @property integer $general_pay_is_coupon
 * @property integer $admin_id
 * @property string $general_pay_admin_name
 * @property integer $worker_id
 * @property integer $handle_admin_id
 * @property string $general_pay_handle_admin_id
 * @property string $general_pay_verify
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class GeneralPay extends \common\models\GeneralPay
{
    public $partner;
    public $pay_type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%general_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'general_pay_source_name','general_pay_money','general_pay_source_name'], 'required'],
            [['customer_id', 'order_id', 'general_pay_source', 'general_pay_mode', 'general_pay_status', 'general_pay_is_coupon', 'admin_id', 'worker_id', 'handle_admin_id', 'created_at', 'updated_at', 'is_reconciliation'], 'integer'],
            [['general_pay_money', 'general_pay_actual_money'], 'number'],
            [['general_pay_source_name'], 'string', 'max' => 20],
            [['general_pay_transaction_id'], 'string', 'max' => 40],
            [['order_id', 'general_pay_admin_name', 'general_pay_handle_admin_id'], 'string', 'max' => 30],
            [['customer_id','order_id'],'match','pattern'=>'%^[1-9]\d*$%'],   //必须为数字，不能是0
            [['general_pay_memo'], 'string', 'max' => 255],
            [['general_pay_verify'], 'string', 'max' => 32],
            /**********以下自定义属性**********/
            [['partner'], 'required'],
            //支付宝,银联,百度钱包,微信
            [['partner'], 'in','range'=>['2088801136967007','898111448161364','1500610004','1217983401']],

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
            'pay'       =>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode'],
            //在线支付
            'online_pay'=>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode','order_id'],
        ];
    }
}
