<?php

namespace dbbase\models\payment;

use dbbase\models\finance\FinanceOrderChannel;
use dbbase\models\finance\FinancePayChannel;

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
        return $this->save();
        //return
            $this->save();
        dump($this->errors);
    }

    /**
     * 验证数据之前组装数据

    public function beforeValidate()
    {
        //订单渠道
        $orderChannelInfo = FinanceOrderChannel::get_order_channel_info($this->order_channel_id);
        $this->pay_channel_id = $orderChannelInfo->pay_channel_id;
        //支付渠道名称
        $this->payment_customer_trans_record_pay_channel = FinancePayChannel::getPayChannelByName($orderChannelInfo->pay_channel_id);
        //订单渠道名称
        $this->payment_customer_trans_record_order_channel = $orderChannelInfo->finance_order_channel_name;
        //交易方式:1消费,2=充值,3=退款,4=补偿
        $this->payment_customer_trans_record_mode_name = self::getCustomerTransRecordModeByName($this->payment_customer_trans_record_mode);
        //makeSign
        $this->payment_customer_trans_record_verify = $this->makeSign();

        return true;
    }
     */

    /**
     * 制造签名
     */
    public function makeSign()
    {
        //加密字符串
        $str='';
        //排除的字段
        $notArray = ['id','payment_customer_trans_record_verify','created_at','updated_at'];
        //获取字段
        $key = $this->attributeLabels();

        //加密签名
        foreach( $key as $name=>$val )
        {
            if( !empty($this->$name) && $this->$name != 1 && $this->$name > 0 && !in_array($name,$notArray))
            {
                $str .= (int)$this->$name;
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
            [['order_id','customer_id', 'payment_customer_trans_record_order_channel', 'pay_channel_id', 'payment_customer_trans_record_pay_channel', 'payment_customer_trans_record_mode_name', 'payment_customer_trans_record_refund_money', 'payment_customer_trans_record_verify'], 'required'],
            [['customer_id', 'order_channel_id',  'pay_channel_id', 'payment_customer_trans_record_mode',  'created_at', 'updated_at'], 'integer'],
            [['payment_customer_trans_record_coupon_money', 'payment_customer_trans_record_cash', 'payment_customer_trans_record_pre_pay', 'payment_customer_trans_record_online_pay', 'payment_customer_trans_record_online_balance_pay', 'payment_customer_trans_record_service_card_pay','payment_customer_trans_record_service_card_current_balance','payment_customer_trans_record_service_card_befor_balance', 'payment_customer_trans_record_refund_money',  'payment_customer_trans_record_order_total_money', 'payment_customer_trans_record_total_money', 'payment_customer_trans_record_current_balance', 'payment_customer_trans_record_befor_balance','payment_customer_trans_record_compensate_money'], 'number'],
            //[['payment_customer_trans_record_service_card_on'], 'string', 'max' => 30],
            //[['payment_customer_trans_record_transaction_id'], 'string', 'max' => 40],
            [['payment_customer_trans_record_remark'], 'string', 'max' => 255],
            [['payment_customer_trans_record_verify'], 'string', 'max' => 320],
            [['customer_id','order_channel_id','pay_channel_id'],'match','pattern'=>'%^[1-9]\d*$%'],   //必须为数字，不能是0
        ];
    }
    // 1jiajie.com246760760210001000285591000
    // 1jiajie.com24676076021000285591000
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
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_online_pay', //在线支付
                'payment_customer_trans_record_online_balance_pay', //在线余额支付
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_service_card_on', //服务卡号
                'payment_customer_trans_record_service_card_pay',    //服务卡支付
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_transaction_id', //交易流水号
                'payment_customer_trans_record_verify',
            ],
            //2=现金 cardPay
            '2'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_cash',   //现金支付
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_verify',
            ],
            //3=预付费 perPay
            '3'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_pre_pay',    //预付费
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
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
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_online_balance_pay',//在线余额支付
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_verify',
            ],
            //9=退款（订单）：把订单金额原路退回 refundSourc
            '9'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_online_pay', //线上支付
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_online_balance_pay', //余额支付
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_refund_money',   //退款金额
                'payment_customer_trans_record_transaction_id', //交易流水号
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡内容
                'payment_customer_trans_record_verify',
            ],
            //1=在线支付（在线）onlinePay
            '10'=>[
                'customer_id',  //用户ID
                'order_id', //订单ID
                'order_channel_id', //订单渠道
                'payment_customer_trans_record_order_channel',  //订单渠道名称
                'pay_channel_id',   //支付渠道
                'payment_customer_trans_record_pay_channel',    //支付渠道名称
                'payment_customer_trans_record_mode',   //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_mode_name',  //交易方式:1消费,2=充值,3=退款,4=补偿
                'payment_customer_trans_record_coupon_money',   //优惠券金额
                'payment_customer_trans_record_online_pay', //在线支付
                'payment_customer_trans_record_order_total_money',  //订单总额
                'payment_customer_trans_record_transaction_id', //交易流水号
                'payment_customer_trans_record_befor_balance',  //之前余额
                'payment_customer_trans_record_current_balance',    //当前余额
                'payment_customer_trans_record_service_card_current_balance',    //服务卡当前
                'payment_customer_trans_record_service_card_befor_balance', //服务卡之前余额
                'payment_customer_trans_record_total_money',    //交易总额
                'payment_customer_trans_record_service_card_on', //服务卡ID
                'payment_customer_trans_record_service_card_pay', //服务卡金额
                'payment_customer_trans_record_verify',

            ],
        ];
    }


    /**
     * 入口分配
     */
    /*
    public function add()
    {
        $obj = null;
        switch($this->scenario){
            case 1:
                //在线支付（在线+余额+服务卡）
                $obj = $this->onlineCradBalancePay();
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
            case 10:
                //在线支付（在线）
                $obj = $this->onlinePay();
                break;
        }

        return $obj;
    }
*/
    /**
     * 在线支付(1)

    private function onlineCradBalancePay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->payment_customer_trans_record_befor_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //当前余额
        $this->payment_customer_trans_record_current_balance = bcsub($lastResult['payment_customer_trans_record_current_balance'],$this->payment_customer_trans_record_online_balance_pay);
        //服务卡之前余额
        $this->payment_customer_trans_record_service_card_befor_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->payment_customer_trans_record_service_card_current_balance = bcsub($lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'],$this->payment_customer_trans_record_service_card_pay);
        //交易总额
        $this->payment_customer_trans_record_total_money = bcadd($lastResult['payment_customer_trans_record_total_money'],$this->payment_customer_trans_record_order_total_money);

        return $this->insert();
    }
     */
    /**
     * 现金支付(2)

    private function cardPay(){
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->payment_customer_trans_record_befor_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //当前余额
        $this->payment_customer_trans_record_current_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //服务卡之前余额
        $this->payment_customer_trans_record_service_card_befor_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->payment_customer_trans_record_service_card_current_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //交易总额
        $this->payment_customer_trans_record_total_money = bcadd($lastResult['payment_customer_trans_record_total_money'],$this->payment_customer_trans_record_order_total_money);

        return $this->insert();
    }
     */
    /**
     * 预付费(3)

    private function perPay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->payment_customer_trans_record_befor_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //当前余额
        $this->payment_customer_trans_record_current_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //服务卡之前余额
        $this->payment_customer_trans_record_service_card_befor_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->payment_customer_trans_record_service_card_current_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //交易总额
        $this->payment_customer_trans_record_total_money = bcadd($lastResult['payment_customer_trans_record_total_money'],$this->payment_customer_trans_record_order_total_money);

        return $this->insert();
    }
     */
    /**
     *  充值（服务卡）(4)

    private function rechargeServiceCardPay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->payment_customer_trans_record_befor_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //当前余额
        $this->payment_customer_trans_record_current_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //服务卡之前余额
        $this->payment_customer_trans_record_service_card_befor_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->payment_customer_trans_record_service_card_current_balance = bcadd($lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'],$this->payment_customer_trans_record_service_card_pay);
        //交易总额
        $this->payment_customer_trans_record_total_money = bcadd($lastResult['payment_customer_trans_record_total_money'],$this->payment_customer_trans_record_order_total_money);

        return $this->insert();

    }
     */

    /**
     * 退款(服务卡)(5)

    private function refundServiceCardPay()
    {

    }
     */
    /**
     * 补偿(6)

    private function compensation()
    {

    }

     */
    /**
     * 服务卡(在线支付)(7)

    private function onlineServiceCardPay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->payment_customer_trans_record_befor_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //当前余额
        $this->payment_customer_trans_record_current_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //服务卡之前余额
        $this->payment_customer_trans_record_service_card_befor_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->payment_customer_trans_record_service_card_current_balance = bcsub($lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'],$this->payment_customer_trans_record_service_card_pay);
        //交易总额
        $this->payment_customer_trans_record_total_money = bcadd($lastResult['payment_customer_trans_record_total_money'],$this->payment_customer_trans_record_order_total_money);

        return $this->insert();
    }
     */
    /**
     * 余额在线支付(8)

    public function onlineBalancePay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->payment_customer_trans_record_befor_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //当前余额
        $this->payment_customer_trans_record_current_balance = bcsub($lastResult['payment_customer_trans_record_current_balance'] , $this->payment_customer_trans_record_online_balance_pay);
        //服务卡之前余额
        $this->payment_customer_trans_record_service_card_befor_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->payment_customer_trans_record_service_card_current_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //交易总额
        $this->payment_customer_trans_record_total_money = bcadd($lastResult['payment_customer_trans_record_total_money'],$this->payment_customer_trans_record_order_total_money);
        return $this->insert();
    }
     */
    /**
     * 退款（订单）：把订单金额原路退回(9)

    private function refundSource()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->payment_customer_trans_record_befor_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //当前余额
        $this->payment_customer_trans_record_current_balance = bcadd($lastResult['payment_customer_trans_record_current_balance'], $this->payment_customer_trans_record_online_balance_pay);
        //服务卡之前余额
        $this->payment_customer_trans_record_service_card_befor_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->payment_customer_trans_record_service_card_current_balance = bcadd($lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] , $this->payment_customer_trans_record_service_card_pay);
        //交易总额
        $this->payment_customer_trans_record_total_money = bcsub($lastResult['payment_customer_trans_record_total_money'],$this->payment_customer_trans_record_order_total_money);
        //退款总额
        $this->payment_customer_trans_record_refund_money = bcadd(bcadd(bcadd($this->payment_customer_trans_record_coupon_money, $this->payment_customer_trans_record_service_card_pay),$this->payment_customer_trans_record_online_balance_pay),$this->payment_customer_trans_record_online_pay);

        return $this->insert();
    }
     */
    /**
     * 在线支付（在线）(10)

    private function onlinePay()
    {
        //获取最后一次结果
        $lastResult = $this->lastResult();
        //获取最后一次服务卡结果
        $lastResultServiceCard = $this->lastResultServerCard();
        //保留两位小数
        bcscale(2);
        //之前余额
        $this->payment_customer_trans_record_befor_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //当前余额
        $this->payment_customer_trans_record_current_balance = $lastResult['payment_customer_trans_record_current_balance'] ? $lastResult['payment_customer_trans_record_current_balance'] : 0;
        //服务卡之前余额
        $this->payment_customer_trans_record_service_card_befor_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //服务卡当前余额
        $this->payment_customer_trans_record_service_card_current_balance = $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] ? $lastResultServiceCard['payment_customer_trans_record_service_card_current_balance'] : 0;
        //交易总额
        $this->payment_customer_trans_record_total_money = bcadd($lastResult['payment_customer_trans_record_total_money'],$this->payment_customer_trans_record_order_total_money);

        return $this->insert();
    }
     */
    /**
     * 获取用户交易记录最后一条数据

    private function lastResult(){
        return $result = PaymentCustomerTransRecord::find()->select(
            [
                'customer_id',
                'payment_customer_trans_record_total_money',
                'payment_customer_trans_record_current_balance',
                'payment_customer_trans_record_befor_balance',
            ]
        )->where(['customer_id'=>$this->customer_id])->orderBy(['id' => SORT_DESC])->asArray()->one();
    }
     */
    /**
     * 获取用户交易记录最后一次服务卡的余额

    private function lastResultServerCard()
    {
        return $result = PaymentCustomerTransRecord::find()->select(
            [
                'customer_id',
                'payment_customer_trans_record_service_card_on',
                'payment_customer_trans_record_service_card_pay',
                'payment_customer_trans_record_service_card_befor_balance',
                'payment_customer_trans_record_service_card_current_balance',
            ]
        )->where(
            [
                'customer_id'=>$this->customer_id,
                'payment_customer_trans_record_service_card_on'=>$this->payment_customer_trans_record_service_card_on
            ]
        )->orderBy(['id' => SORT_DESC])->asArray()->one();
    }
     */
    /**
     * 记录模式
     * @param $mode_id
     * @return string

    public static function getCustomerTransRecordModeByName($mode_id)
    {
        switch($mode_id){
            case 1 :
                $mode_name = '消费';
                break;
            case 2 :
                $mode_name = '充值';
                break;
            case 3 :
                $mode_name = '退款';
                break;
            case 4 :
                $mode_name = '补偿';
                break;
        }
        return $mode_name;
    }
     */
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
