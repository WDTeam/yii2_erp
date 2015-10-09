<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%customer_trans_record_log}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $order_id
 * @property integer $order_channel_id
 * @property integer $customer_trans_record_order_channel
 * @property integer $pay_channel_id
 * @property integer $customer_trans_record_pay_channel
 * @property integer $customer_trans_record_mode
 * @property integer $customer_trans_record_mode_name
 * @property string $customer_trans_record_promo_code_money
 * @property string $customer_trans_record_coupon_money
 * @property string $customer_trans_record_cash
 * @property string $customer_trans_record_pre_pay
 * @property string $customer_trans_record_online_pay
 * @property string $customer_trans_record_online_balance_pay
 * @property string $customer_trans_record_online_service_card_on
 * @property string $customer_trans_record_online_service_card_pay
 * @property string $customer_trans_record_online_service_card_current_balance
 * @property string $customer_trans_record_online_service_card_befor_balance
 * @property string $customer_trans_record_compensate_money
 * @property string $customer_trans_record_refund_money
 * @property string $customer_trans_record_money
 * @property string $customer_trans_record_order_total_money
 * @property string $customer_trans_record_total_money
 * @property string $customer_trans_record_current_balance
 * @property string $customer_trans_record_befor_balance
 * @property string $customer_trans_record_transaction_id
 * @property string $customer_trans_record_remark
 * @property string $customer_trans_record_verify
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerTransRecordLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_trans_record_log}}';
    }

    /**
     * 自动处理创建时间和修改时间
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    /**
     * 验证数据之前组装数据
     */
    public function beforeValidate()
    {
        //支付渠道
        $payChannel = FinancePayChannel::findOne($this->pay_channel_id);
        //支付渠道名称
        $this->customer_trans_record_pay_channel = $payChannel->finance_pay_channel_name;
        //交易方式:1消费,2=充值,3=退款,4=补偿
        switch($this->customer_trans_record_mode){
            case 1 :
                $this->customer_trans_record_mode_name = '消费';
                break;
            case 2 :
                $this->customer_trans_record_mode_name = '充值';
                break;
            case 3 :
                $this->customer_trans_record_mode_name = '退款';
                break;
            case 4 :
                $this->customer_trans_record_mode_name = '补偿';
                break;
        }
        //订单渠道
        $orderChannel = FinanceOrderChannel::findOne($this->order_channel_id);
        //订单渠道名称
        $this->customer_trans_record_order_channel = $orderChannel->finance_order_channel_name;
        //makeSign
        $this->customer_trans_record_verify = $this->makeSign();
        return true;
    }

    /**
     * 制造签名
     */
    private function makeSign()
    {
        //加密字符串
        $str='';
        //排除的字段
        $notArray = ['updated_at'];
        //获取字段
        $key = $this->attributeLabels();
        //加密签名
        foreach( $key as $name=>$val )
        {
            if( !empty($this->$name) && $this->$name != 1 && !in_array($name,$notArray))
            {
                $str .= $this->$name;
            }
        }
        return md5(md5($str).'1jiajie.com');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'order_id', 'order_channel_id', 'customer_trans_record_order_channel', 'pay_channel_id', 'customer_trans_record_pay_channel', 'customer_trans_record_mode', 'customer_trans_record_mode_name', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_trans_record_promo_code_money', 'customer_trans_record_coupon_money', 'customer_trans_record_cash', 'customer_trans_record_pre_pay', 'customer_trans_record_online_pay', 'customer_trans_record_online_balance_pay', 'customer_trans_record_online_service_card_pay', 'customer_trans_record_online_service_card_current_balance', 'customer_trans_record_online_service_card_befor_balance', 'customer_trans_record_compensate_money', 'customer_trans_record_refund_money', 'customer_trans_record_money', 'customer_trans_record_order_total_money', 'customer_trans_record_total_money', 'customer_trans_record_current_balance', 'customer_trans_record_befor_balance'], 'number'],
            [['customer_trans_record_online_service_card_on'], 'string', 'max' => 30],
            [['customer_trans_record_transaction_id'], 'string', 'max' => 40],
            [['customer_trans_record_remark'], 'string', 'max' => 255],
            [['customer_trans_record_verify'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', '用户ID'),
            'order_id' => Yii::t('app', '订单ID'),
            'order_channel_id' => Yii::t('app', '订单渠道'),
            'customer_trans_record_order_channel' => Yii::t('app', '订单渠道名称'),
            'pay_channel_id' => Yii::t('app', '支付渠道'),
            'customer_trans_record_pay_channel' => Yii::t('app', '支付渠道名称'),
            'customer_trans_record_mode' => Yii::t('app', '交易方式:1消费,2=充值,3=退款,4=赔偿'),
            'customer_trans_record_mode_name' => Yii::t('app', '交易方式名称'),
            'customer_trans_record_promo_code_money' => Yii::t('app', '优惠码金额'),
            'customer_trans_record_coupon_money' => Yii::t('app', '优惠券金额'),
            'customer_trans_record_cash' => Yii::t('app', '现金支付'),
            'customer_trans_record_pre_pay' => Yii::t('app', '预付费金额（第三方）'),
            'customer_trans_record_online_pay' => Yii::t('app', '在线支付'),
            'customer_trans_record_online_balance_pay' => Yii::t('app', '在线余额支付'),
            'customer_trans_record_online_service_card_on' => Yii::t('app', '服务卡号'),
            'customer_trans_record_online_service_card_pay' => Yii::t('app', '服务卡支付'),
            'customer_trans_record_online_service_card_current_balance' => Yii::t('app', '服务卡当前余额'),
            'customer_trans_record_online_service_card_befor_balance' => Yii::t('app', '服务卡之前余额'),
            'customer_trans_record_compensate_money' => Yii::t('app', '补偿金额'),
            'customer_trans_record_refund_money' => Yii::t('app', '退款金额'),
            'customer_trans_record_money' => Yii::t('app', '余额支付'),
            'customer_trans_record_order_total_money' => Yii::t('app', '订单总额'),
            'customer_trans_record_total_money' => Yii::t('app', '交易总额'),
            'customer_trans_record_current_balance' => Yii::t('app', '当前余额'),
            'customer_trans_record_befor_balance' => Yii::t('app', '之前余额'),
            'customer_trans_record_transaction_id' => Yii::t('app', '交易流水号'),
            'customer_trans_record_remark' => Yii::t('app', '备注'),
            'customer_trans_record_verify' => Yii::t('app', '验证'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_del' => Yii::t('app', '删除'),
        ];
    }

    /**
     * @inheritdoc
     * @return CustomerTransRecordLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerTransRecordLogQuery(get_called_class());
    }
}
