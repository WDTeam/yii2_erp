<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%customer_trans_record}}".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $order_id
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
 * @property string $customer_trans_record_refund_money
 * @property string $customer_trans_record_money
 * @property string $customer_trans_record_order_total_money
 * @property string $customer_trans_record_total_money
 * @property string $customer_trans_record_current_balance
 * @property string $customer_trans_record_befor_balance
 * @property string $customer_trans_record_transaction_id
 * @property string $customer_trans_record_remark
 * @property string $customer_trans_record_verify
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_del
 */
class CustomerTransRecord extends \yii\db\ActiveRecord
{
    public $record_type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_trans_record}}';
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

    public function ccc()
    {
        echo "test";
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
            [['customer_id', 'customer_trans_record_order_channel', 'pay_channel_id', 'customer_trans_record_pay_channel', 'customer_trans_record_mode_name', 'customer_trans_record_refund_money', 'customer_trans_record_verify'], 'required'],
            [['customer_id', 'order_id', 'order_channel_id',  'pay_channel_id', 'customer_trans_record_mode',  'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_trans_record_promo_code_money', 'customer_trans_record_coupon_money', 'customer_trans_record_cash', 'customer_trans_record_pre_pay', 'customer_trans_record_online_pay', 'customer_trans_record_online_balance_pay', 'customer_trans_record_online_service_card_pay','customer_trans_record_online_service_card_current_balance','customer_trans_record_online_service_card_befor_balance', 'customer_trans_record_refund_money', 'customer_trans_record_money', 'customer_trans_record_order_total_money', 'customer_trans_record_total_money', 'customer_trans_record_current_balance', 'customer_trans_record_befor_balance','customer_trans_record_compensate_money'], 'number'],
            [['customer_trans_record_online_service_card_on'], 'string', 'max' => 30],
            [['customer_trans_record_transaction_id'], 'string', 'max' => 40],
            [['customer_trans_record_remark'], 'string', 'max' => 255],
            [['customer_trans_record_verify'], 'string', 'max' => 32],
            [['record_type'],'required'],   //自定义，交易类型:1=消费,2=充值,3=退款,4=补偿
            [['customer_id','order_id','order_channel_id','pay_channel_id'],'match','pattern'=>'%^[1-9]\d*$%'],   //必须为数字，不能是0
        ];
    }

    /**
     * 场景验证
     * @param string $record_type_交易类型:
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
            //1=在线支付（在线+余额+服务卡）onlinePay
            '1'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'customer_trans_record_pay_channel',    //支付渠道名称
                'customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_promo_code_money',   //优惠码金额
                'customer_trans_record_coupon_money',   //优惠券金额
                'customer_trans_record_online_pay', //在线支付
                'customer_trans_record_online_balance_pay', //在线余额支付
                'customer_trans_record_online_service_card_on', //服务卡号
                'customer_trans_record_online_service_card_pay',    //服务卡支付
                'customer_trans_record_order_total_money',  //订单总额
                'customer_trans_record_transaction_id', //交易流水号
            ],
            //2=现金 cardPay
            '2'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'customer_trans_record_pay_channel',    //支付渠道名称
                'customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_order_total_money',  //订单总额
                'customer_trans_record_cash', //现金支付
            ],
            //3=预付费 perPay
            '3'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'customer_trans_record_pay_channel',    //支付渠道名称
                'customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_order_total_money',  //订单总额
                'customer_trans_record_pre_pay', //预付费支付
                'customer_trans_record_transaction_id', //交易流水号
            ],
            //4=充值（服务卡） rechargeServiceCardPay
            '4'=>[
                'customer_id',  //用户ID
                'order_channel_id', //订单渠道
                'customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'customer_trans_record_pay_channel',    //支付渠道名称
                'customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_order_total_money',  //订单总额
                'customer_trans_record_online_service_card_on', //服务卡号
                'customer_trans_record_online_service_card_pay',    //服务卡支付
                'customer_trans_record_transaction_id', //交易流水号
            ],
            //5=退款（服务卡）：把订单金额退到服务卡 refundServiceCardPay
            '5'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'customer_trans_record_pay_channel',    //支付渠道名称
                'customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_order_total_money',  //订单总额
                'customer_trans_record_refund_money',   //退款金额
                'customer_trans_record_online_service_card_on', //服务卡号
                'customer_trans_record_online_service_card_pay',    //服务卡支付金额
            ],
            //6=补偿  compensation
            '6'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'customer_trans_record_pay_channel',    //支付渠道名称
                'customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_order_total_money',  //订单总额
                'customer_trans_record_compensate_money',   //补偿金额
                'customer_trans_record_online_service_card_on', //服务卡号
                'customer_trans_record_online_service_card_pay',    //服务卡支付金额
            ],
            //7=服务卡(在线支付) onlineServiceCardPay
            '7'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'customer_trans_record_pay_channel',    //支付渠道名称
                'customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_order_total_money',  //订单总额
                'customer_trans_record_online_service_card_on', //服务卡号
                'customer_trans_record_online_service_card_pay',    //服务卡支付金额
            ],
            //8=余额（在线支付）onlineBalancePay
            '8'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'customer_trans_record_pay_channel',    //支付渠道名称
                'customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_online_balance_pay',//在线余额支付
                'customer_trans_record_order_total_money',  //订单总额
            ],
            //9=退款（订单）：把订单金额原路退回 refundSourc
            '9'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'customer_trans_record_pay_channel',    //支付渠道名称
                'customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'customer_trans_record_order_total_money',  //订单总额
                'customer_trans_record_refund_money',   //退款金额
                'customer_trans_record_online_service_card_on', //服务卡号
                'customer_trans_record_online_service_card_pay',    //服务卡支付金额
                'customer_trans_record_transaction_id', //交易流水号
            ],
        ];
    }


    /**
     * 入口分配
     */
    public function add()
    {
        $obj = null;
        switch($this->scenario){
            case 1:
                //在线支付（在线+余额+服务卡）
                $obj = $this->onlinePay();
                break;
            case 2:
                //现金
                $obj = $this->cardPay();
                break;
            case 3:
                //预付费
                $obj = $this->perPay();
                break;
            case 4:
                //充值（服务卡）
                $obj = $this->rechargeServiceCardPay();
                break;
            case 5:
                //退款（服务卡）
                $obj = $this->refundServiceCardPay();
                break;
            case 6:
                //补偿
                $obj = $this->compensation();
                break;
            case 7:
                //服务卡(在线支付)
                $obj = $this->onlineServiceCardPay();
                break;
            case 8:
                //余额（在线支付）
                $obj = $this->onlineBalancePay();
                break;
            case 9:
                //退款（订单）：把订单金额原路退回
                $obj = $this->refundSource();
                break;
        }

        return $obj;
    }

    /**
     * 在线支付
     */
    private function onlinePay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->customer_trans_record_befor_balance = $lastResult['customer_trans_record_befor_balance'] ? $lastResult['customer_trans_record_befor_balance'] : 0;
        //当前余额
        $this->customer_trans_record_current_balance = bcsub($lastResult['customer_trans_record_current_balance'],$this->customer_trans_record_online_balance_pay);
        //服务卡之前余额
        $this->customer_trans_record_online_service_card_befor_balance = $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] ? $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->customer_trans_record_online_service_card_current_balance = bcsub($lastResultServiceCard['customer_trans_record_online_service_card_current_balance'],$this->customer_trans_record_online_service_card_pay);

        return $this->insert();
    }

    /**
     * 现金支付
     */
    private function cardPay(){

    }

    /**
     *  充值（服务卡）
     */
    private function rechargeServiceCardPay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->customer_trans_record_befor_balance = $lastResult['customer_trans_record_befor_balance'] ? $lastResult['customer_trans_record_befor_balance'] : 0;
        //当前余额
        $this->customer_trans_record_current_balance = $lastResult['customer_trans_record_current_balance'] ? $lastResult['customer_trans_record_current_balance'] : 0;
        //服务卡之前余额
        $this->customer_trans_record_online_service_card_befor_balance = $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] ? $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->customer_trans_record_online_service_card_current_balance = bcadd($lastResultServiceCard['customer_trans_record_online_service_card_current_balance'],$this->customer_trans_record_online_service_card_pay);

        return $this->insert();

    }
    /**
     * 预付费
     */
    private function perPay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->customer_trans_record_befor_balance = $lastResult['customer_trans_record_befor_balance'] ? $lastResult['customer_trans_record_befor_balance'] : 0;
        //当前余额
        $this->customer_trans_record_current_balance = $lastResult['customer_trans_record_current_balance'] ? $lastResult['customer_trans_record_current_balance'] : 0;
        //服务卡之前余额
        $this->customer_trans_record_online_service_card_befor_balance = $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] ? $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->customer_trans_record_online_service_card_current_balance = $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] ? $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] : 0;

        return $this->insert();
    }

    /**
     * 退款(服务卡)
     */
    private function refundServiceCardPay()
    {

    }

    /**
     * 补偿
     */
    private function compensation()
    {

    }


    /**
     * 服务卡(在线支付)
     */
    private function onlineServiceCardPay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->customer_trans_record_befor_balance = $lastResult['customer_trans_record_befor_balance'] ? $lastResult['customer_trans_record_befor_balance'] : 0;
        //当前余额
        $this->customer_trans_record_current_balance = $lastResult['customer_trans_record_befor_balance'] ? $lastResult['customer_trans_record_befor_balance'] : 0;
        //服务卡之前余额
        $this->customer_trans_record_online_service_card_befor_balance = $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] ? $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->customer_trans_record_online_service_card_current_balance = bcsub($lastResultServiceCard['customer_trans_record_online_service_card_current_balance'],$this->customer_trans_record_online_service_card_pay);

        return $this->insert();
    }

    /**
     * 余额在线支付
     */
    public function onlineBalancePay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();

        //保留两位小数
        bcscale(2);
        //之前余额
        $this->customer_trans_record_befor_balance = $lastResult['customer_trans_record_befor_balance'] ? $lastResult['customer_trans_record_befor_balance'] : 0;
        //当前余额
        $this->customer_trans_record_current_balance = bcsub($lastResult['customer_trans_record_befor_balance'] , $this->customer_trans_record_online_balance_pay);
        //服务卡之前余额
        $this->customer_trans_record_online_service_card_befor_balance = $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] ? $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->customer_trans_record_online_service_card_current_balance = $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] ? $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] : 0;

        return $this->insert();
    }

    /**
     * 退款（订单）：把订单金额原路退回
     */
    private function refundSource()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->customer_trans_record_befor_balance = $lastResult['customer_trans_record_befor_balance'] ? $lastResult['customer_trans_record_befor_balance'] : 0;
        //当前余额
        $this->customer_trans_record_current_balance = $lastResult['customer_trans_record_befor_balance'] ? $lastResult['customer_trans_record_befor_balance'] : 0;
        //服务卡之前余额
        $this->customer_trans_record_online_service_card_befor_balance = $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] ? $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->customer_trans_record_online_service_card_current_balance = $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] ? $lastResultServiceCard['customer_trans_record_online_service_card_current_balance'] : 0;

        return $this->insert();
    }

    /**
     * 获取用户交易记录最后一条数据
     */
    private function lastResult(){
        return $result = CustomerTransRecord::find()->select(
            [
                'customer_id',
                'customer_trans_record_total_money',
                'customer_trans_record_current_balance',
                'customer_trans_record_befor_balance',
            ]
        )->where(['customer_id'=>$this->customer_id])->orderBy(['id' => SORT_DESC])->asArray()->one();
    }

    /**
     * 获取用户交易记录最后一次服务卡的余额
     */
    private function lastResultServerCard()
    {
        return $result = CustomerTransRecord::find()->select(
            [
                'customer_id',
                'customer_trans_record_online_service_card_on',
                'customer_trans_record_online_service_card_pay',
                'customer_trans_record_online_service_card_befor_balance',
                'customer_trans_record_online_service_card_current_balance',
            ]
        )->where(
            [
                'customer_id'=>$this->customer_id,
                'customer_trans_record_online_service_card_on'=>$this->customer_trans_record_online_service_card_on
            ]
        )->orderBy(['id' => SORT_DESC])->asArray()->one();
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
            'customer_trans_record_mode' => Yii::t('app', '交易方式:1消费,2=充值,3=退款,4=补偿'),
            'customer_trans_record_mode_name' => Yii::t('app', '交易方式名称'),
            'customer_trans_record_promo_code_money' => Yii::t('app', '优惠码金额'),
            'customer_trans_record_coupon_money' => Yii::t('app', '优惠券金额'),
            'customer_trans_record_cash' => Yii::t('app', '现金支付'),
            'customer_trans_record_pre_pay' => Yii::t('app', '预付费金额（第三方）'),
            'customer_trans_record_online_pay' => Yii::t('app', '在线支付'),
            'customer_trans_record_online_balance_pay' => Yii::t('app', '在线余额支付'),
            'customer_trans_record_online_service_card_on' => Yii::t('app', '服务卡号'),
            'customer_trans_record_online_service_card_pay' => Yii::t('app', '服务卡支付'),
            'customer_trans_record_online_service_card_befor_balance' => Yii::t('app', '服务卡之前余额'),
            'customer_trans_record_online_service_card_current_balance' => Yii::t('app', '服务卡当前余额'),
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
            'record_type' => Yii::t('app', '规则'),
        ];
    }
}
