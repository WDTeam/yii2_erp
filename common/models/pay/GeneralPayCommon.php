<?php

namespace common\models\pay;

use core\models\Customer;
use core\models\CustomerTransRecord\CustomerTransRecord;
use core\models\order\Order;
use core\models\order\OrderSearch;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
/**
 * This is the model class for table "{{%general_pay}}".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $order_id
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
 * @property string $admin_id
 * @property string $general_pay_admin_name
 * @property string $worker_id
 * @property string $handle_admin_id
 * @property string $general_pay_handle_admin_name
 * @property string $general_pay_verify
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_reconciliation
 */
class GeneralPayCommon extends \yii\db\ActiveRecord
{
    public $partner;
    public $pay_type;

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
        return '{{%general_pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_name','customer_mobile','customer_address','order_source_url','page_url','detail','openid','customer_id', 'general_pay_source_name','general_pay_money','general_pay_source_name'], 'required'],
            [['customer_id', 'order_id', 'general_pay_source', 'general_pay_mode', 'general_pay_status', 'general_pay_is_coupon', 'admin_id', 'worker_id', 'handle_admin_id', 'created_at', 'updated_at', 'is_reconciliation'], 'integer'],
            [['general_pay_money', 'general_pay_actual_money'], 'number'],
            [['general_pay_source_name'], 'string', 'max' => 20],
            [['general_pay_transaction_id'], 'string', 'max' => 40],
            [['order_id', 'general_pay_admin_name', 'general_pay_handle_admin_name'], 'string', 'max' => 30],
            [['customer_id','order_id'],'match','pattern'=>'%^[1-9]\d*$%'],   //必须为数字，不能是0
            [['general_pay_memo','show_url','return_url'], 'string', 'max' => 255],
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
            //支付宝WEB
            'alipay_web_pay'    =>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode','return_url','show_url'],
            //支付宝WEB
            'alipay_web_online_pay'    =>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode','order_id','return_url','show_url'],
            //在线充值
            'pay'       =>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode'],
            //在线支付
            'online_pay'=>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode','order_id'],
            //微信在线充值
            'wx_h5_pay' =>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode','openid'],
            //微信在线支付
            'wx_h5_online_pay'=>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode','order_id','openid'],
            //微信在线充值
            'zhidahao_h5_pay' =>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode','customer_name','customer_mobile','customer_address','order_source_url','page_url','detail'],
            //微信在线支付
            'zhidahao_h5_online_pay'=>['general_pay_money','customer_id','partner','general_pay_source','general_pay_source_name','general_pay_mode','order_id','customer_name','customer_mobile','customer_address','order_source_url','page_url','detail'],
            //在线退款
            'refund'       =>['customer_id','order_id','general_pay_money','general_pay_source','general_pay_source_name','general_pay_mode','general_pay_status','general_pay_eo_order_id','general_pay_is_coupon','admin_id','general_pay_admin_name','general_pay_verify'],
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
     * 回调地址
     * @param $type_name 类型
     */
    public function notify_url($type_name)
    {
        $http = "http://".$_SERVER['HTTP_HOST']."/".yii::$app->controller->id."/".$type_name."-notify";
        return $http;
    }

    /**
     * 判断在线充值还是支付
     * @return string
     */
    public function subject()
    {
        return $subject = empty($this->order_id) ? 'e家洁会员充值' : 'e家洁在线支付';
    }

    /**
     * 判断在线充值还是支付
     * @return string
     */
    public function body()
    {
        return $body = empty($this->order_id) ? 'e家洁会员充值'.$this->general_pay_money.'元' : 'e家洁在线支付'.$this->general_pay_money.'元';
    }

    /**
     * 生成第三方订单号
     * 年月日+交易类型+随机数+自增ID
     * 01 正常订单 02 退款 03 赔付
     * @return bool|string 订单号
     */
    public function create_out_trade_no($type=1,$order_id=0)
    {
        $id = empty($this->id) ?  $order_id : $this->id;
        if( empty($id) ) return false;
        switch($type)
        {
            case 1 :
                $transType = '01';
                break;
            case 2 :
                $transType = '02';
                break;
            case 3 :
                $transType = '03';
                break;
        }
        //组装支付订单号
        $rand = mt_rand(1000,9999);
        $date = date("ymd",time());
        return $date.$transType.$rand.$id;
    }

    /**
     * 转换金额
     * @param $money1   实际金额
     * @param $money2   基数
     * @param $method   +,-,*,%
     * @param $num 保留几位小树
     * @return float    实际金额
     */
    public function toMoney($money1, $money2, $method = '*',$num=2)
    {
        $toMoney = '';
        bcscale($num); //保留两位小数
        switch($method)
        {
            case '+' :
                $toMoney = bcadd($money1,$money2);
                break;
            case '-' :
                $toMoney = bcsub($money1,$money2);
                break;
            case '*' :
                $toMoney = bcmul($money1,$money2);
                break;
            case '/' :
                $toMoney = bcdiv($money1,$money2);
                break;
        }
        return $toMoney;
    }

    /**
     * 获取订单信息
     * @param $order_id
     */
    public static function orderInfo($order_id){
        $orderSearch = new OrderSearch();
        $orderInfo = $orderSearch->getOne($order_id);
        return $orderInfo;
    }

    /**
     * 订单支付
     * @param $attribute 支付或订单详细数据
     */
    public static function orderPay($attribute)
    {
        //查询用户信息
        $orderInfo = self::orderInfo($attribute['order_id']);
        $attribute['customer_trans_record_online_service_card_on'] = !empty($orderInfo->orderExtPay->card_id) ? $orderInfo->orderExtPay->card_id : 0;    //服务卡ID
        $attribute['customer_trans_record_online_service_card_pay'] = !empty($orderInfo->orderExtPay->order_use_card_money) ? $orderInfo->orderExtPay->order_use_card_money : 0;//服务卡金额
        $attribute['customer_trans_record_coupon_money'] = !empty($orderInfo->orderExtPay->order_use_coupon_money) ? $orderInfo->orderExtPay->order_use_coupon_money : 0; //优惠券金额
        $attribute['customer_trans_record_online_balance_pay'] = !empty($orderInfo->orderExtPay->order_use_acc_balance) ? $orderInfo->orderExtPay->order_use_acc_balance : 0;  //余额支付
        $attribute['customer_trans_record_order_total_money'] = $orderInfo->order_money;  //订单总额
        $attribute['order_pop_order_money'] = !empty($orderInfo->orderExtPop->order_pop_order_money) ? $orderInfo->orderExtPop->order_pop_order_money : 0;  //预付费

        //服务卡扣费
        if( !empty($attribute['customer_trans_record_online_service_card_on']) && !empty($attribute['customer_trans_record_online_service_card_pay']) ){
            //Customer::decBalance($model->customer_id,$orderInfo->orderExtPay->order_use_acc_balance);
        }elseif( !empty($attribute['customer_trans_record_online_balance_pay']) ){
            //余额扣费
            Customer::decBalance($attribute['customer_id'],$orderInfo->orderExtPay->order_use_acc_balance);
        }
        //支付订单交易记录
        CustomerTransRecord::analysisRecord($attribute);
        //修改订单状态
        /**
         * @param $order_id 订单ID
         * @param $admin_id 后台管理员ID 系统0 客户1
         * @param $pay_channel_id  支付渠道ID
         * @param $order_pay_channel_name   支付渠道名称
         * @param $order_pay_flow_num   支付流水号
         */
        //验证支付金额是否一致
        if( $attribute['general_pay_money'] == $attribute['general_pay_actual_money'] )
        {
            $orderChannel = FinanceOrderChannel::get_order_channel_info($attribute['general_pay_source']);
            Order::isPaymentOnline($attribute['order_id'],0,$orderChannel['id'],$orderChannel['finance_pay_channel_name'],$attribute['general_pay_transaction_id']);
        }

    }

    /**
     * 充值支付
     * @param $attribute 支付或订单详细数据
     */
    public static function pay($attribute)
    {
        //支付充值
        Customer::incBalance($attribute['customer_id'],$attribute['general_pay_actual_money']);

        //充值交易记录
        CustomerTransRecord::analysisRecord($attribute);
        return true;
    }

    /**
     * 订单支付退款
     * @param $data
     */
    public static function orderRefund($data)
    {
        $orderInfo = self::orderInfo($data['order_id']);
        //获取余额支付
        $balancePay = $orderInfo->orderExtPay->order_use_acc_balance;  //余额支付

        //获取服务卡支付
        $service_card_on = $orderInfo->orderExtPay->card_id;    //服务卡ID
        $service_card_pay = $orderInfo->orderExtPay->order_use_card_money;   //服务卡内容

        //执行自有退款
        if( !empty($balancePay) ){
            //余额支付退款
            Customer::incBalance($data['customer_id'],$balancePay);
        }elseif( !empty($service_card_on) && !empty($service_card_pay) ){
            //服务卡支付退款
        }
    }

    /**
     * 获取记录ID
     * @param $out_trade_no 交易ID
     */
    public function getGeneralPayId($out_trade_no)
    {
        return substr($out_trade_no,12);
    }

    /**
     * 制造签名
     */
    public function makeSign()
    {
        //加密字符串
        $str='';
        //排除的字段
        $notArray = ['updated_at','is_reconciliation'];
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
     * 返回支付数据
     * @param $order_id 订单ID
     * @param $general_pay_status 支付状态
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getPayStatus($order_id,$general_pay_status){
        $where = ['order_id'=>$order_id,'general_pay_status'=>$general_pay_status];
        return GeneralPay::find()->where($where)->one();
    }

    /**
     * 支付成功发送短信
     * @param $customer_id 用户ID
     */
    public function smsSend($data)
    {
        $phone = Customer::getCustomerPhone($data->data['customer_id']);
        $msg = !empty($data->data['msg']) ? $data->data['msg'] : '支付成功!!!';
        Yii::$app->sms->send($phone,$msg);
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
            'general_pay_money' => Yii::t('app', '发起充值/交易金额'),
            'general_pay_actual_money' => Yii::t('app', '实际充值/交易金额'),
            'general_pay_source' => Yii::t('app', '数据来源:1=APP微信,2=H5微信,3=APP百度钱包,4=APP银联,5=APP支付宝,6=WEB支付宝,7=HT淘宝,8=H5百度直达号,9=HT刷卡,10=HT现金,11=HT刷卡'),
            'general_pay_source_name' => Yii::t('app', '数据来源名称'),
            'general_pay_mode' => Yii::t('app', '交易方式:1=消费,2=充值,3=退款,4=补偿'),
            'general_pay_status' => Yii::t('app', '状态：0=失败,1=成功'),
            'general_pay_transaction_id' => Yii::t('app', '第三方交易流水号'),
            'general_pay_eo_order_id' => Yii::t('app', '商户ID(第三方交易)'),
            'general_pay_memo' => Yii::t('app', '备注'),
            'general_pay_is_coupon' => Yii::t('app', '是否返券'),
            'admin_id' => Yii::t('app', '管理员ID'),
            'general_pay_admin_name' => Yii::t('app', '管理员名称'),
            'worker_id' => Yii::t('app', '销售卡阿姨ID'),
            'handle_admin_id' => Yii::t('app', '办卡人ID'),
            'general_pay_handle_admin_name' => Yii::t('app', '办卡人名称'),
            'general_pay_verify' => Yii::t('app', '支付验证'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_reconciliation' => Yii::t('app', '是否对账'),
        ];
    }

}
