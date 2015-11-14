<?php

namespace dbbase\models\payment;

use Yii;
use yii\base\Exception;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;

class Payment extends \yii\db\ActiveRecord
{
    //微信
    public $openid;
    //直达号
    public $customer_name; //商品名称
    public $customer_mobile; //用户电话
    public $customer_address; //用户地址
    public $order_source_url; //订单详情地址
    public $page_url; //订单跳转地址
    public $detail; //订单详情
    //支付宝WEB
    public $show_url; //订单显示地址
    public $return_url; //订单同步回调地址

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','customer_name','customer_mobile','customer_address','order_source_url','page_url','detail','openid','customer_id', 'payment_source','payment_money','payment_channel_id'], 'required'],
            [['customer_id', 'payment_source', 'payment_mode', 'payment_status', 'payment_type', 'admin_id', 'worker_id', 'handle_admin_id', 'created_at', 'updated_at', 'is_reconciliation'], 'integer'],
            [['payment_money', 'payment_actual_money'], 'number'],
            [['payment_channel_name'], 'string', 'max' => 20],
            [['payment_transaction_id'], 'string', 'max' => 40],
            [['payment_admin_name', 'payment_handle_admin_name'], 'string', 'max' => 30],
            [['customer_id'],'match','pattern'=>'%^[1-9]\d*$%'],   //必须为数字，不能是0
            [['payment_memo','show_url','return_url'], 'string', 'max' => 255],
            [['payment_verify'], 'string', 'max' => 32],
            [['payment_type'],'in','range'=>[1,2,3,4]],   //支付类型:1普通订单支付,2周期订单支付,3充值
        ];
    }

    /**
     * 场景验证
     * @param alipay_web_online_pay 支付宝WEB
     * @param online_pay 在线支付
     * @param wx_h5_online_pay  微信在线支付
     * @param zhidahao_h5_online_pay    直达号在线支付
     * @param refund    在线退款
     */

    public function scenarios()
    {
        return[
            'default'                => ['id','payment_actual_money','payment_transaction_id','payment_eo_order_id'],
            //支付宝WEB
            'alipay_web_online_pay' =>  ['payment_type','payment_money','customer_id','customer_phone','payment_source','payment_channel_id','payment_channel_name','payment_mode','order_id','order_code','order_batch_code','return_url','show_url'],
            //在线支付
            'online_pay'            =>  ['payment_type','payment_money','customer_id','customer_phone','payment_source','payment_channel_id','payment_channel_name','payment_mode','order_id','order_code','order_batch_code'],
            //微信在线支付
            'wx_h5_online_pay'      =>  ['payment_type','payment_money','customer_id','customer_phone','payment_source','payment_channel_id','payment_channel_name','payment_mode','order_id','order_code','order_batch_code','openid'],
            //直达号在线支付
            'zhidahao_h5_online_pay'=>  ['payment_type','payment_money','customer_id','customer_phone','payment_source','payment_channel_id','payment_channel_name','payment_mode','order_id','order_code','order_batch_code','customer_name','customer_mobile','customer_address','order_source_url','page_url','detail'],
            //在线退款
            'refund'                =>  ['customer_id','order_id','payment_money','payment_actual_money','payment_source','payment_channel_id','payment_channel_name','payment_mode','payment_status','payment_memo','payment_type','admin_id','payment_admin_name','payment_verify'],
        ];
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
     * 创建/修改数据
     */
    protected function doSave(){
        return $this->save();
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
            'payment_money' => Yii::t('app', '发起充值/交易金额'),
            'payment_actual_money' => Yii::t('app', '实际充值/交易金额'),
            'payment_source' => Yii::t('app', '数据来源'),
            'payment_channel_id' => Yii::t('app', '支付渠道ID'),
            'payment_channel_name' => Yii::t('app', '支付渠道名称'),
            'payment_mode' => Yii::t('app', '交易方式:1=消费,2=充值,3=退款,4=补偿'),
            'payment_status' => Yii::t('app', '状态：0=失败,1=成功'),
            'payment_transaction_id' => Yii::t('app', '第三方交易流水号'),
            'payment_eo_order_id' => Yii::t('app', '商户ID(第三方交易)'),
            'payment_memo' => Yii::t('app', '备注'),
            'payment_type' => Yii::t('app', '支付类型'),
            'admin_id' => Yii::t('app', '管理员ID'),
            'payment_admin_name' => Yii::t('app', '管理员名称'),
            'worker_id' => Yii::t('app', '销售卡阿姨ID'),
            'handle_admin_id' => Yii::t('app', '办卡人ID'),
            'payment_handle_admin_name' => Yii::t('app', '办卡人名称'),
            'payment_verify' => Yii::t('app', '支付验证'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_reconciliation' => Yii::t('app', '是否对账'),
        ];
    }


}
