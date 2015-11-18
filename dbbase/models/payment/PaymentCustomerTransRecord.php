<?php

namespace dbbase\models\payment;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%payment_customer_trans_record}}".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $order_id
 * @property integer $order_channel_id
 * @property integer $payment_customer_trans_record_order_channel
 * @property integer $pay_channel_id
 * @property integer $payment_customer_trans_record_pay_channel
 * @property integer $payment_customer_trans_record_mode
 * @property integer $payment_customer_trans_record_mode_name
 * @property string $payment_customer_trans_record_coupon_money
 * @property string $payment_customer_trans_record_cash
 * @property string $payment_customer_trans_record_pre_pay
 * @property string $payment_customer_trans_record_online_pay
 * @property string $payment_customer_trans_record_online_balance_pay
 * @property string $payment_customer_trans_record_service_card_on
 * @property string $payment_customer_trans_record_service_card_pay
 * @property string $payment_customer_trans_record_refund_money
 * @property string $payment_customer_trans_record_order_total_money
 * @property string $payment_customer_trans_record_total_money
 * @property string $payment_customer_trans_record_current_balance
 * @property string $payment_customer_trans_record_befor_balance
 * @property string $payment_customer_trans_record_transaction_id
 * @property string $payment_customer_trans_record_remark
 * @property string $payment_customer_trans_record_verify
 * @property string $created_at
 * @property string $updated_at
 */
class PaymentCustomerTransRecord extends \yii\db\ActiveRecord
{
    const PAYMENT_TRANS_TECORD_PREFIX = 'PAYMENT_TRANS_RECORD_CUSTOMER_';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%payment_customer_trans_record}}';
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
     * 新增/修改数据
     */
    protected function doSave()
    {
        try{
            //删除缓存
            $cacheId = PaymentCustomerTransRecord::PAYMENT_TRANS_TECORD_PREFIX.$this->customer_id;
            Yii::$app->redis->executeCommand('del', [$cacheId]);
        }catch(Exception $e){}
        return $this->save(false);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id','customer_id','customer_phone', 'payment_customer_trans_record_order_channel', 'pay_channel_id', 'payment_customer_trans_record_pay_channel', 'payment_customer_trans_record_mode_name', 'payment_customer_trans_record_refund_money', 'payment_customer_trans_record_verify'], 'required'],
            [['customer_id', 'order_channel_id',  'pay_channel_id', 'payment_customer_trans_record_mode',  'created_at', 'updated_at'], 'integer'],
            [['payment_customer_trans_record_coupon_money', 'payment_customer_trans_record_cash', 'payment_customer_trans_record_pre_pay', 'payment_customer_trans_record_online_pay', 'payment_customer_trans_record_online_balance_pay', 'payment_customer_trans_record_service_card_pay','payment_customer_trans_record_service_card_current_balance','payment_customer_trans_record_service_card_befor_balance', 'payment_customer_trans_record_refund_money',  'payment_customer_trans_record_order_total_money', 'payment_customer_trans_record_total_money', 'payment_customer_trans_record_current_balance', 'payment_customer_trans_record_befor_balance','payment_customer_trans_record_compensate_money'], 'number'],
            [['payment_customer_trans_record_service_card_on'], 'string', 'max' => 30],
            [['payment_customer_trans_record_transaction_id'], 'string', 'max' => 40],
            [['order_code','order_batch_code'], 'string', 'max' => 64],
            [['payment_customer_trans_record_remark'], 'string', 'max' => 255],
            [['payment_customer_trans_record_verify'], 'string', 'max' => 320],
            [['customer_id','order_channel_id','pay_channel_id'],'match','pattern'=>'%^[1-9]\d*$%'],   //必须为数字，不能是0
        ];
    }

    /**
     * 场景验证
     * @remark 1：在线支付（在线+余额+服务卡）
     *         2：现金
     *         3：预付费
     *         4：充值（服务卡）
     *         5：退款（服务卡）：把订单金额退到服务卡
     *         6：补偿
     *         7：服务卡
     *         8：余额
     *         9：退款（订单）：把订单金额原路退回
     */
    public function scenarios()
    {
        return[
            //1=在线支付（在线+余额+服务卡）onlineCradBalancePay
            '1'=>[

                'customer_id',  //用户ID
                'customer_phone', //用户手机号码

                'admin_id',  //管理员ID
                'admin_name',  //管理员姓名

                //订单相关
                'order_id', //订单ID
                'order_code', //订单编号
                'order_batch_code', //周期订单编号
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称

                //支付相关
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称

                //类型相关
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式名称:1消费,2=充值,3=退款,4=补偿

                //优惠券相关
                'payment_customer_trans_record_coupon_id',   //优惠券ID
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_coupon_code',   //优惠券CODE
                'payment_customer_trans_record_coupon_transaction_id',   //优惠券交易流水号

                //余额支付相关
                'payment_customer_trans_record_online_balance_pay',//在线余额支付
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_balance_transaction_id', //服务卡交易流水号

                //服务卡支付相关
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_service_card_transaction_id',    //服务卡交易流水号

                //订单相关
                'payment_customer_trans_record_eo_order_id',    //商户订单号
                'payment_customer_trans_record_transaction_id', //交易流水号
                'payment_customer_trans_record_online_pay', //在线支付
                'payment_customer_trans_record_order_total_money', //订单总额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_verify',

            ],
            //2=现金 cardPay
            '2'=>[
                'customer_id',  //用户ID
                'customer_phone', //用户手机号码

                'admin_id',  //管理员ID
                'admin_name',  //管理员姓名

                //订单相关
                'order_id', //订单ID
                'order_code', //订单编号
                'order_batch_code', //周期订单编号
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称

                //支付相关
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称

                //类型相关
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式名称:1消费,2=充值,3=退款,4=补偿

                //优惠券相关
                'payment_customer_trans_record_coupon_id',   //优惠券ID
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_coupon_code',   //优惠券CODE
                'payment_customer_trans_record_coupon_transaction_id',   //优惠券交易流水号

                //余额支付相关
                'payment_customer_trans_record_online_balance_pay',//在线余额支付
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_balance_transaction_id', //服务卡交易流水号

                //服务卡支付相关
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_service_card_transaction_id',    //服务卡交易流水号

                //订单相关
                'payment_customer_trans_record_eo_order_id',    //商户订单号
                'payment_customer_trans_record_cash',   //现金支付
                'payment_customer_trans_record_order_total_money', //订单总额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_verify',

            ],
            //3=预付费 perPay
            '3'=>[
                'customer_id',  //用户ID
                'customer_phone', //用户手机号码

                'admin_id',  //管理员ID
                'admin_name',  //管理员姓名

                //订单相关
                'order_id', //订单ID
                'order_code', //订单编号
                'order_batch_code', //周期订单编号
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称

                //支付相关
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称

                //类型相关
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式名称:1消费,2=充值,3=退款,4=补偿

                //优惠券相关
                'payment_customer_trans_record_coupon_id',   //优惠券ID
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_coupon_code',   //优惠券CODE
                'payment_customer_trans_record_coupon_transaction_id',   //优惠券交易流水号

                //余额支付相关
                'payment_customer_trans_record_online_balance_pay',//在线余额支付
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_balance_transaction_id', //服务卡交易流水号

                //服务卡支付相关
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_service_card_transaction_id',    //服务卡交易流水号

                //订单相关
                'payment_customer_trans_record_eo_order_id',    //商户订单号
                'payment_customer_trans_record_pre_pay',    //预付费
                'payment_customer_trans_record_order_total_money', //订单总额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_verify',
            ],
            //4=充值（服务卡） rechargeServiceCardPay
            '4'=>[
                'customer_id',  //用户ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_online_pay', //在线支付
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_service_card_on', //服务卡号
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_transaction_id', //交易流水号
                'payment_customer_trans_record_eo_order_id',//商户订单号
                'payment_customer_trans_record_verify',
            ],
            //5=退款（服务卡）：把订单金额退到服务卡 refundServiceCardPay
            '5'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_refund_money',   //退款金额
                'payment_customer_trans_record_service_card_on', //服务卡号
                'payment_customer_trans_record_service_card_pay',    //服务卡支付金额
                'payment_customer_trans_record_verify',
            ],
            //6=补偿  compensation
            '6'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_compensate_money',   //补偿金额
                'payment_customer_trans_record_service_card_on', //服务卡号
                'payment_customer_trans_record_service_card_pay',    //服务卡支付金额
                'payment_customer_trans_record_verify',
            ],
            //7=服务卡(在线支付) onlineServiceCardPay
            '7'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_verify',
            ],
            //8=余额（在线支付）onlineBalancePay
            '8'=>[
                'customer_id',  //用户ID
                'customer_phone', //用户手机号码

                'admin_id',  //管理员ID
                'admin_name',  //管理员姓名

                //订单相关
                'order_id', //订单ID
                'order_code', //订单编号
                'order_batch_code', //周期订单编号
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称

                //支付相关
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称

                //类型相关
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式名称:1消费,2=充值,3=退款,4=补偿

                //优惠券相关
                'payment_customer_trans_record_coupon_id',   //优惠券ID
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_coupon_code',   //优惠券CODE
                'payment_customer_trans_record_coupon_transaction_id',   //优惠券交易流水号

                //余额支付相关
                'payment_customer_trans_record_online_balance_pay',//在线余额支付
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_balance_transaction_id', //服务卡交易流水号

                //服务卡支付相关
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_service_card_transaction_id',    //服务卡交易流水号

                //订单相关
                'payment_customer_trans_record_eo_order_id',    //商户订单号
                'payment_customer_trans_record_order_total_money', //订单总额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_verify',
            ],
            //9=退款（订单）：把订单金额原路退回 refundSourc
            '9'=>[
                'customer_id',  //用户ID
                'customer_phone', //用户手机号码

                'admin_id',  //管理员ID
                'admin_name',  //管理员姓名

                //订单相关
                'order_id', //订单ID
                'order_code', //订单编号
                'order_batch_code', //周期订单编号
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称

                //支付相关
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称

                //类型相关
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式名称:1消费,2=充值,3=退款,4=补偿

                //优惠券相关
                'payment_customer_trans_record_coupon_id',   //优惠券ID
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_coupon_code',   //优惠券CODE
                'payment_customer_trans_record_coupon_transaction_id',   //优惠券交易流水号

                //余额支付相关
                'payment_customer_trans_record_online_balance_pay',//在线余额支付
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_balance_transaction_id', //服务卡交易流水号

                //服务卡支付相关
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_service_card_transaction_id',    //服务卡交易流水号

                //订单相关
                'payment_customer_trans_record_eo_order_id',    //商户订单号
                'payment_customer_trans_record_order_total_money', //订单总额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_refund_money',   //退款总额
                'payment_customer_trans_record_verify',
            ],
            //1=在线支付（在线）onlinePay
            '10'=>[
                'customer_id',  //用户ID
                'customer_phone', //用户手机号码

                'admin_id',  //管理员ID
                'admin_name',  //管理员姓名

                //订单相关
                'order_id', //订单ID
                'order_code', //订单编号
                'order_batch_code', //周期订单编号
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称

                //支付相关
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称

                //类型相关
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式名称:1消费,2=充值,3=退款,4=补偿

                //优惠券相关
                'payment_customer_trans_record_coupon_id',   //优惠券ID
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_coupon_code',   //优惠券CODE
                'payment_customer_trans_record_coupon_transaction_id',   //优惠券交易流水号

                //余额支付相关
                'payment_customer_trans_record_online_balance_pay',//在线余额支付
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_balance_transaction_id', //服务卡交易流水号

                //服务卡支付相关
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_service_card_transaction_id',    //服务卡交易流水号

                //订单相关
                'payment_customer_trans_record_eo_order_id',    //商户订单号
                'payment_customer_trans_record_transaction_id', //交易流水号
                'payment_customer_trans_record_online_pay', //在线支付
                'payment_customer_trans_record_order_total_money', //订单总额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_verify',
            ],
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
            'order_code' => Yii::t('app', '订单编号'),
            'order_batch_code' => Yii::t('app', '周期订单编号'),
            'order_channel_id' => Yii::t('app', '订单渠道'),
            'payment_customer_trans_record_order_channel' => Yii::t('app', '订单渠道名称'),
            'pay_channel_id' => Yii::t('app', '支付渠道'),
            'payment_customer_trans_record_pay_channel' => Yii::t('app', '支付渠道名称'),
            'payment_customer_trans_record_mode' => Yii::t('app', '交易方式:1消费,2=充值,3=退款,4=补偿'),
            'payment_customer_trans_record_mode_name' => Yii::t('app', '交易方式名称'),
            'payment_customer_trans_record_coupon_money' => Yii::t('app', '优惠券金额'),
            'payment_customer_trans_record_cash' => Yii::t('app', '现金支付'),
            'payment_customer_trans_record_pre_pay' => Yii::t('app', '预付费金额（第三方）'),
            'payment_customer_trans_record_online_pay' => Yii::t('app', '在线支付'),
            'payment_customer_trans_record_online_balance_pay' => Yii::t('app', '在线余额支付'),
            'payment_customer_trans_record_service_card_on' => Yii::t('app', '服务卡号'),
            'payment_customer_trans_record_service_card_pay' => Yii::t('app', '服务卡支付'),
            'payment_customer_trans_record_service_card_befor_balance' => Yii::t('app', '服务卡之前余额'),
            'payment_customer_trans_record_service_card_current_balance' => Yii::t('app', '服务卡当前余额'),
            'payment_customer_trans_record_compensate_money' => Yii::t('app', '补偿金额'),
            'payment_customer_trans_record_refund_money' => Yii::t('app', '退款金额'),
            'payment_customer_trans_record_order_total_money' => Yii::t('app', '订单总额'),
            'payment_customer_trans_record_total_money' => Yii::t('app', '交易总额'),
            'payment_customer_trans_record_current_balance' => Yii::t('app', '当前余额'),
            'payment_customer_trans_record_befor_balance' => Yii::t('app', '之前余额'),
            'payment_customer_trans_record_transaction_id' => Yii::t('app', '交易流水号'),
            'payment_customer_trans_record_remark' => Yii::t('app', '备注'),
            'payment_customer_trans_record_verify' => Yii::t('app', '验证'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
        ];
    }
}
