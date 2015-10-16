<?php

namespace common\models;

use core\models\Customer;
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
 * @property string $general_pay_handle_admin_idy
 * @property string $general_pay_verify
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_reconciliation
 */
class GeneralPay extends \yii\db\ActiveRecord
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
     * 分配支付渠道
     */
    public function call_pay()
    {
        $fun = $this->pay_type;
        return $this->$fun();
    }


    /**
     * @param $source_id    来源ID
     * @return string   来源名称
     * @remark  数据来源:
     * 1=APP微信,
     * 2=H5微信,
     * 3=APP百度钱包,
     * 4=APP银联,
     * 5=APP支付宝,
     * 6=WEB支付宝,
     * 7=HT淘宝,
     * 8=H5百度直达号,
     * 9=HT刷卡,
     * 10=HT现金,
     */
    public function source($source_id)
    {
        $source = '';
        if(empty($source_id)) return $source;
        //获取订单渠道名称
        $source = FinanceOrderChannel::getOrderChannelByName($source_id);
        switch($source_id){
            case 1:
                $this->pay_type = 'wx_app';
                break;
            case 2:
                $this->pay_type = 'wx_h5';
                break;
            case 3:
                $this->pay_type = 'bfb_app';
                break;
            case 4:
                $this->pay_type = 'up_app';
                break;
            case 5:
                $this->pay_type = 'alipay_app';
                break;
            case 6:
                $this->pay_type = 'alipay_web';
                break;
            case 7:
                $this->pay_type = 'pay_ht';
                break;
            case 8:
                $this->pay_type = 'zhidahao_h5';
                break;
            case 9:
                $this->pay_type = 'pay_ht';
                break;
            case 10:
                $this->pay_type = 'pay_ht';
                break;
        }
        return $source;
    }

    /**
     * 微信APP(1)
     */
    private function wx_app()
    {
        $param = [
            "body"	=> $this->body(),
            "out_trade_no"	=> $this->create_out_trade_no(),
            "general_pay_money"	=> $this->toMoney($this->general_pay_money,100,'*',0),
            'time_start' => date("YmdHis"),
            'time_expire' => date("YmdHis", time() + 600000),
            "trade_type" => "WX",
            "subject" => $this->subject(),
            "notify_url" => $this->notify_url('wx-app'),
        ];
        $class = new \wxpay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 微信H5(2)
     */
    private function wx_h5()
    {
        $param = [
            "body"	=> $this->body(),
            "out_trade_no"	=> $this->create_out_trade_no(),
            "general_pay_money"	=> $this->toMoney($this->general_pay_money,100,'*',0),
            'time_start' => date("YmdHis"),
            'time_expire' => date("YmdHis", time() + 600000),
            "trade_type" => "JSAPI",
            "subject" => $this->subject(),
            "notify_url" => $this->notify_url('wx-h5'),
        ];
        $class = new \wxjspay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 百度钱包APP(3)
     */
    private function bfb_app()
    {
        $param = [
            'out_trade_no'=>$this->create_out_trade_no(),
            'subject'=>$this->subject(),
            'body'=>$this->body(),
            'general_pay_money'=>$this->toMoney($this->general_pay_money,100,'*'),
            'notify_url'=>$this->notify_url('bfb-app'),
        ];

        $class = new \bfbpay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 银联APP(4)
     */
    private function up_app()
    {
        $param = [
            'out_trade_no'=>$this->create_out_trade_no(),
            'subject'=>$this->subject(),
            'general_pay_money'=>$this->toMoney($this->general_pay_money,100,'*',0),
            'notify_url'=>$this->notify_url('up-app'),
        ];
        $class = new \uppay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 支付宝APP
     */
    private function alipay_app()
    {
        $param = [
            'out_trade_no'=>$this->create_out_trade_no(),
            'subject'=>$this->subject(),
            'body'=>$this->body(),
            'general_pay_money'=>$this->general_pay_money,
            'notify_url'=>$this->notify_url('alipay-app'),
        ];
        $class = new \alipay_class();
        $msg = $class->get($param);
        return $msg;
    }


    /**
     * 支付宝WEB
     * 第三方支付思路：
     * 1 客户端请求服务端，带有指定数据
     * 2 服务端将支付所需参数返回给客户端
     * 3 服务端创建支付记录（未支付状态）
     */
    private function alipay_web(){}
    private function pay_ht(){}
    private function zhidahao_h5(){}

    /**
     * 回调地址
     * @param $type_name 类型
     */
    private function notify_url($type_name)
    {
        $http = "http://".$_SERVER['HTTP_HOST']."/general-pay/".$type_name."-notify";
        return $http;
    }

    /**
     * 判断在线充值还是支付
     * @return string
     */
    private function subject()
    {
        return $subject = empty($this->order_id) ? 'e家洁会员充值' : 'e家洁在线支付';
    }

    /**
     * 判断在线充值还是支付
     * @return string
     */
    private function body()
    {
        return $body = empty($this->order_id) ? 'e家洁会员充值'.$this->general_pay_money.'元' : 'e家洁在线支付'.$this->general_pay_money.'元';
    }

    /**
     * 生成第三方订单号
     * @return bool|string 订单号
     */
    private function create_out_trade_no()
    {
        if(empty($this->id)) return false;
        //组装支付订单号
        $rand = mt_rand(1000,9999);
        $date = date("ymd",time());
        return $date.$rand.$this->id;
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
     * 订单支付
     * @param $attribute 支付或订单详细数据
     */
    public static function orderPay($attribute)
    {
        //查询用户信息
        $orderSearch = new \core\models\order\OrderSearch;
        $orderInfo = $orderSearch->search(['OrderSearch'=>['id'=>$attribute['order_id']]])->query->one();
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
     * 获取记录ID
     * @param $out_trade_no 交易ID
     */
    public function getGeneralPayId($out_trade_no)
    {
        return substr($out_trade_no,10);
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
     * 订单退款
     * @param $out_trade_no    商户订单号
     * @param $transaction_id   交易流水号
     * @param $out_refund_no    退款订单号
     * @param $total_fee    订单总金额(单位/分)
     * @param $refund_fee   退款金额(单位/分)
     * @param $op_user_passwd   退款密码(MD5)
     */
    public function wx_app_refund( $out_refund_no,$total_fee,$refund_fee,$op_user_passwd,$out_trade_no=0,$transaction_id=0 )
    {
        if( empty($out_trade_no) && empty($transaction_id) ){
            return $msg = "out_trade_no和transaction_id至少一个必填，同时存在时transaction_id优先";
        }

        $params = [
            'out_refund_no' => $out_refund_no,
            'out_trade_no' => $out_trade_no,   //订单号
            'transaction_id' => $transaction_id, //交易流水号
            'total_fee' => $total_fee,      //交易总额(分单位)
            'refund_fee' => $refund_fee,     //退款总额(分单位)
            'op_user_passwd' => $op_user_passwd, //操作员密码,MD5处理
        ];

        $class = new \wxrefund_class();
        $data = $class->refund($params);
        return $data;
    }


    /**
     * @param int $out_trade_no     支付订单号
     * @param int $transaction_id   支付交易流水号
     * @param int $out_refund_no    退款交易流水号
     * @param int $refund_id        退款订单号
     */
    public function wx_app_refund_query( $out_trade_no=0,$transaction_id=0,$out_refund_no=0,$refund_id=0 )
    {
        if( empty($out_trade_no) && empty($transaction_id) ){
            return $msg = "out_trade_no和transaction_id、out_refund_no、refund_id至少一个必填";
        }

        $params = [
            'out_trade_no' => $out_trade_no,    //支付订单号
            'transaction_id' => $transaction_id,   //支付交易流水号
            'out_refund_no' => $out_refund_no, //退款交易流水号
            'refund_id' => $refund_id,      //退款订单号
        ];

        $class = new \wxrefund_class();
        $data = $class->refundQuery($params);
        return $data;
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
            'general_pay_mode' => Yii::t('app', '交易方式:1=充值,2=余额支付,3=在线支付,4=退款,5=赔偿'),
            'general_pay_status' => Yii::t('app', '状态：0=失败,1=成功'),
            'general_pay_transaction_id' => Yii::t('app', '第三方交易流水号'),
            'general_pay_eo_order_id' => Yii::t('app', '商户ID(第三方交易)'),
            'general_pay_memo' => Yii::t('app', '备注'),
            'general_pay_is_coupon' => Yii::t('app', '是否返券'),
            'admin_id' => Yii::t('app', '管理员ID'),
            'general_pay_admin_name' => Yii::t('app', '管理员名称'),
            'worker_id' => Yii::t('app', '销售卡阿姨ID'),
            'handle_admin_id' => Yii::t('app', '办卡人ID'),
            'general_pay_handle_admin_id' => Yii::t('app', '办卡人名称'),
            'general_pay_verify' => Yii::t('app', '支付验证'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_reconciliation' => Yii::t('app', '是否对账'),
        ];
    }

}
