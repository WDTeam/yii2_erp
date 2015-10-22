<?php

namespace common\models;

use core\models\Customer;
use core\models\CustomerTransRecord\CustomerTransRecord;
use core\models\order\OrderStatus;
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

    public $openid;
    //直达号
    public $customer_name; //商品名称
    public $customer_mobile; //用户电话
    public $customer_address; //用户地址
    public $order_source_url; //订单详情地址
    public $page_url; //订单跳转地址
    public $detail; //订单详情

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
     * 分配支付渠道
     */
    public function call_pay($data)
    {
        $fun = $this->pay_type;
        return $this->$fun($data);
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
     * 7=H5百度直达号,
     * 20=后台支付,
     * 21=微博支付,
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
                $this->pay_type = 'zhidahao_h5';
                break;
            case 20:
                $this->pay_type = 'pay_ht';
                break;
            case 21:
                $this->pay_type = 'weibo_h5';
                break;
        }
        return $source;
    }

    /**
     * 微信APP(1)
     */
    private function wx_app($data)
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
    private function wx_h5($data)
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
            'openid' => $data['openid'],//'o7Kvajh91Fmh_KYzhwX0LWZtpMPM',//$data['openid'],
        ];

        $class = new \wxjspay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 百度钱包APP(3)
     */
    private function bfb_app($data)
    {
        $param = [
            'out_trade_no'=>$this->create_out_trade_no(),
            'subject'=>$this->subject(),
            'body'=>$this->body(),
            'general_pay_money'=>$this->toMoney($this->general_pay_money,100,'*',0),
            'notify_url'=>$this->notify_url('bfb-app'),
        ];

        $class = new \bfbpay_class();
        $msg = $class->get($param);
        return $msg;
    }

    /**
     * 银联APP(4)
     */
    private function up_app($data)
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
     * 支付宝APP(5)
     */
    private function alipay_app($data)
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
     * 支付宝WEB(6)
     * 第三方支付思路：
     * 1 客户端请求服务端，带有指定数据
     * 2 服务端将支付所需参数返回给客户端
     * 3 服务端创建支付记录（未支付状态）
     */
    private function alipay_web(){}

    /**
     * 直达号支付(7)
     */
    private function zhidahao_h5($data)
    {
        $param = [
            'out_trade_no'=>$this->create_out_trade_no(),
            'goods_name'=>$data['goods_name'],
            'general_pay_money'=>$this->toMoney($this->general_pay_money,100,'*',0),
            'detail' => $data['detail'],
            'order_source_url' => $data['order_source_url'],
            'return_url' => $this->notify_url('zhidahao-h5'),
            'page_url' => $data['page_url'],
            'customer_name' => $data['customer_name'],
            'customer_mobile' => $data['customer_mobile'],
            'customer_address' => $data['customer_address'],
        ];
        $class = new \zhidahao_class();
        $msg = $class->get($param);
        return $msg;
        //echo json_encode(['code'=>'ok','msg'=>$msg]);

    }

    /**
     * 后台支付(20)
     */
    private function pay_ht(){}

    /**
     * 新浪微博支付(21)
     */
    private function weibo_h5(){}

    /**
     * 回调地址
     * @param $type_name 类型
     */
    private function notify_url($type_name)
    {
        $http = "http://".$_SERVER['HTTP_HOST']."/".yii::$app->controller->id."/".$type_name."-notify";
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
        //修改订单状态
        /**
         * @param $order_id 订单ID
         * @param $admin_id 后台管理员ID 系统0 客户1
         * @param $pay_channel_id  支付渠道ID
         * @param $order_pay_channel_name   支付渠道名称
         * @param $order_pay_flow_num   支付流水号
         */
        $orderChannel = FinanceOrderChannel::get_order_channel_info($attribute['general_pay_source']);
        OrderStatus::isPaymentOnline($attribute['order_id'],0,$orderChannel['id'],$orderChannel['finance_pay_channel_name'],$attribute['general_pay_transaction_id']);
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
     * 支付宝APP回调
     */
    public function alipayAppNotify($data)
    {
        //POST数据
        if(!empty($data['debug'])){
            $_POST = array (
                "discount"=> "0.00",
                "payment_type"=> "1",
                "subject"=> "e家洁会员充值",
                "trade_no"=> "2015092510165",
                "buyer_email"=> "lsqpy@163.com",
                "gmt_create"=> "2015-09-25 21:13:20",
                "notify_type"=> "trade_status_sync",
                "quantity"=> "1",
                "out_trade_no"=> "150925846765",
                "seller_id"=> "2088801136967007",
                "notify_time"=> "2015-09-25 21:13:21",
                "body"=> "e家洁会员充值0.01元",
                "trade_status"=> "TRADE_FINISHED",
                "is_total_fee_adjust"=> "N",
                "total_fee"=> "0.01",
                "gmt_payment"=> "2015-09-25 21:13:21",
                "seller_email"=> "47632990@qq.com",
                "gmt_close"=> "2015-09-25 21:13:21",
                "price"=> "0.01",
                "buyer_id"=> "2088002074138164",
                "notify_id"=> "6260ae5cc41e6aa3a42824ec032071df2w",
                "use_coupon"=> "N",
                "sign_type"=> "RSA",
                "sign"=> "T4Bkh9KljoFOTIossu5QtYPRUwj/7by/YLXNQ7efaxe0AwYDjFDFWTFts4h8yq2ceCH8weqYVBklj2btkF2/hKPuUifuJNB6lk8EtHckmJg0MzhGIBAvpteUAo+5Gs+wlI5eS5zmryBskuHOXSM7svb9wNCcL9pHAv8CM06Au+A="
            );
            $post = $_POST;
        }else{
            $post = yii::$app->request->post();
        }

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $post['total_fee'],   //支付金额
            'general_pay_log_shop_name' => $post['subject'],   //商品名称
            'general_pay_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'general_pay_log_transaction_id' => $post['buyer_id'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['trade_status']),   //支付状态
            'general_pay_log_status' => $post['trade_status'],   //支付状态
            'pay_channel_id' => 6,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $GeneralPayId = $this->getGeneralPayId($post['out_trade_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        //验证支付结果
        if(!empty($model))
        {
            //验证签名
            $class = new \alipay_class;
            $verify_result = $class->callback();

            if(!empty($_GET['debug']))
            {
                $verify_result = true;
            }

            //签名验证成功
            if($verify_result)
            {
                $model->id = $GeneralPayId; //ID
                $model->general_pay_status = 1; //支付状态
                $model->general_pay_actual_money = $post['total_fee'];
                $model->general_pay_transaction_id = $post['trade_no'];
                $model->general_pay_is_coupon = 1;
                $model->general_pay_eo_order_id = $post['out_trade_no'];
                $model->general_pay_verify = $model->makeSign();

                //commit
                $connection  = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try
                {
                    $model->save(false);
                    $attribute = $model->getAttributes();
                    if(!empty($model->order_id)){
                        //支付订单
                        GeneralPay::orderPay($attribute);
                    }else{
                        //充值支付
                        GeneralPay::pay($attribute);
                    }

                    $transaction->commit();

                    if(empty($data['debug'])){
                        //发送短信事件
                        $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                        $this->trigger('paySms');
                    }
                    echo $class->notify();
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                }
            }
        }
    }

    /**
     * 微信APP回调
     * 金额单位为【分】
     */
    public function wxAppNotify($data)
    {
        $class = new \wxpay_class();
        if(!empty($data['debug'])){
            $post = $_POST = [
                "r" => "/general-pay/wx-app-notify",
                "bank_type" => "0",
                "discount" => "0",
                "fee_type" => "1",
                "input_charset" => "UTF-8",
                "notify_id" => "envUQL970OImimNqSbr02zP5_Zq5nrw-luZ8ADWHtVsc_30p2GXJ51YmMHoAqccbbeZBlGI2Ken5nHuMzIRqYgLX_4kw4QXg",
                "out_trade_no" => "15101258091",
                "partner" => "1217983401",
                "product_fee" => "1",
                "sign" => "A9A2D759AC57CA47ACC80436C4C6A876",
                "sign_type" => "MD5",
                "time_end" => "20151012165432",
                "total_fee" => "1",
                "trade_mode" => "1",
                "trade_state" => "0",
                "transaction_id" => "1217983401381510128537567810",
                "transport_fee" => "0"
            ];
            $status = 'error';
        }else{
            //调用微信验证
            $post = $data;
        }

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $this->toMoney($post['total_fee'],100,'/'),   //支付金额
            'general_pay_log_shop_name' => '微信支付',   //商品名称
            'general_pay_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'general_pay_log_transaction_id' => $post['transaction_id'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['trade_state']),   //支付状态
            'general_pay_log_status' => $post['trade_state'],   //支付状态
            'pay_channel_id' => 11,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );

        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $GeneralPayId = $this->getGeneralPayId($post['out_trade_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        if(!empty($data['debug'])){
            $status = true;
        }else{
            $status = $class->callback();
        }

        //验证支付结果
        if(!empty($model) && !empty($status)){
            $model->id = $GeneralPayId; //ID
            $model->general_pay_status = 1; //支付状态
            $model->general_pay_actual_money = $model->toMoney($post['total_fee'],100,'/');
            $model->general_pay_transaction_id = $post['transaction_id'];
            $model->general_pay_is_coupon = 1;
            $model->general_pay_eo_order_id = $post['out_trade_no'];
            $model->general_pay_verify = $model->makeSign();

            //commit
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->save(false);
                //change customer balance
                $attribute = $model->getAttributes();
                if(!empty($model->order_id)){
                    //支付订单
                    GeneralPay::orderPay($attribute);
                }else{
                    //充值支付
                    GeneralPay::pay($attribute);
                }
                $transaction->commit();
                $class->notify();

                if(empty($data['debug'])){
                    //发送短信事件
                    $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                    $this->trigger('paySms');
                }

            } catch(Exception $e) {
                $transaction->rollBack();
            }
        }

    }

    /**
     *  百付宝APP回调
     *  金额单位为【分】
     */
    public function bfbAppNotify($data)
    {
        //POST数据
        if(!empty($data['debug'])){
            $_GET = array (
                'bank_no' => '',
                'bfb_order_create_time' => '20150714115504',
                'bfb_order_no' => '2015071415006100041110555687771',
                'buyer_sp_username' => '',
                'currency' => '1',
                'extra' => '',
                'fee_amount' => '0',
                'input_charset' => '1',
                'order_no' => '150927830311',
                'pay_result' => '1',
                'pay_time' => '20150714115503',
                'pay_type' => '2',
                'sign_method' => '1',
                'sp_no' => '1500610004',
                'total_amount' => '1',
                'transport_amount' => '0',
                'unit_amount' => '1',
                'unit_count' => '1',
                'version' => '2',
                'sign' => 'eef8e524ef6b6dde1699b04421fc9bc5',
            ) ;
            $post = $_GET;
        }else{
            $post = $data;
        }

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $this->toMoney($post['total_amount'],100,'/'),   //支付金额
            'general_pay_log_shop_name' => '百付宝',   //商品名称
            'general_pay_log_eo_order_id' => $post['order_no'],   //订单ID
            'general_pay_log_transaction_id' => $post['bfb_order_no'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['pay_result']),   //支付状态
            'general_pay_log_status' => $post['pay_result'],   //支付状态
            'pay_channel_id' => 8,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $GeneralPayId = $this->getGeneralPayId($post['order_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        //验证签名
        $class = new \bfbpay_class();
        if(!empty($_GET['debug'])){
            $sign = $class->callback();
        }else{
            $sign = true;
        }

        //验证支付结果
        if( !empty($model) && !empty($sign) ){
            $model->id = $GeneralPayId; //ID
            $model->general_pay_status = 1; //支付状态
            $model->general_pay_actual_money = $model->toMoney($post['total_amount'],100,'/');
            $model->general_pay_transaction_id = $post['bfb_order_no'];
            $model->general_pay_is_coupon = 1;
            $model->general_pay_eo_order_id = $post['order_no'];
            $model->general_pay_verify = $model->makeSign();

            //commit
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->save(false);
                //change customer balance
                $attribute = $model->getAttributes();
                if(!empty($model->order_id)){
                    //支付订单
                    GeneralPay::orderPay($attribute);
                }else{
                    //充值支付
                    GeneralPay::pay($attribute);
                }

                $transaction->commit();

                if(empty($data['debug'])){
                    //发送短信事件
                    $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                    $this->trigger('paySms');
                }

                $class->notify();
            } catch(Exception $e) {
                $transaction->rollBack();
            }

        }
    }

    /**
     * 银联APP回调
     * @param $data
     * @throws \yii\db\Exception
     */
    public function upAppNotify($data)
    {
        //POST数据
        if(!empty($data['debug'])){
            $_POST = array (
                "accessType" => "0",
                "bizType" => "000201",
                "certId" => "21267647932558653966460913033289351200",
                "currencyCode" => "156",
                "encoding" => "utf-8",
                "merId" => "898111448161364",
                "orderId" => "151010743932",
                "queryId" => "201510101112461438298",
                "reqReserved" => "透传信息",
                "respCode" => "00",
                "respMsg" => "Success!",
                "settleAmt" => "1",
                "settleCurrencyCode" => "156",
                "settleDate" => "1010",
                "signMethod" => "01",
                "traceNo" => "143829",
                "traceTime" => "1010111246",
                "txnAmt" => "1",
                "txnSubType" => "01",
                "txnTime" => "20151010111246",
                "txnType" => "01",
                "version" => "5.0.0",
                "signature" => "GnmVKKUPgdLc11K8zrwL5w5cTx1bieDdTniC2Psh7WEuk4y+53l8OzvE41KsJNyxBuBWAPBgypK+8jNJmGUU2x+tMU5Z0liIKVD5HWhboHxlwZvh0vMGfB8vlmIcbYipxUuWz3Jin11I6O8W6mvTAb76wJXrcbqZD1PKtVP7/5ldxpYsRh/MmEfeDFCcxqMk0uS/ON7XagGKkYSOxCcDMmQ4xRhNzLOthO8vkK6vPDWuowNjFdQXV8A2K9MxVqJNrR5QgR52Hm0dy9z5o09YhjDhMgwlyqRAgaBRbVDNt7qJXFyp3lcxwU9sJBkpOCYV6Cwi/03sWJA+W87U6+gN9Q=="
            );
            $post = $_POST;
        }else{
            $post = yii::$app->request->post();
        }

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $this->toMoney($post['settleAmt'],100,'/'),   //支付金额
            'general_pay_log_shop_name' => $post['reqReserved'],   //商品名称
            'general_pay_log_eo_order_id' => $post['orderId'],   //订单ID
            'general_pay_log_transaction_id' => $post['queryId'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['respMsg']),   //支付状态
            'general_pay_log_status' => $post['respMsg'],   //支付状态
            'pay_channel_id' => 12,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );

        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //获取交易ID
        $GeneralPayId = $this->getGeneralPayId($post['orderId']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        //验证签名
        $class = new \uppay_class();
        $sign = $class->callback();

        //验证支付结果
        if( !empty($model) && !empty($sign) ){

            $model->id = $GeneralPayId; //ID
            $model->general_pay_status = 1; //支付状态
            $model->general_pay_actual_money = $model->toMoney($post['settleAmt'],100,'/');
            $model->general_pay_transaction_id = $post['queryId'];
            $model->general_pay_is_coupon = 1;
            $model->general_pay_eo_order_id = $post['orderId'];
            $model->general_pay_verify = $model->makeSign();

            //commit
            $connection  = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->save(false);
                $attribute = $model->getAttributes();
                if(!empty($model->order_id)){
                    //支付订单
                    GeneralPay::orderPay($attribute);
                }else{
                    //充值支付
                    GeneralPay::pay($attribute);
                }
                $transaction->commit();

                if(empty($data['debug'])){
                    //发送短信事件
                    $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                    $this->trigger('paySms');
                }

                $class->notify();
            } catch(Exception $e) {
                $transaction->rollBack();
            }
        }
    }

    /**
     * 支付宝APP回调
     * wx-js-notify
     */
    public function wxH5Notify($data)
    {
        if(!empty($data['debug'])){
            $GLOBALS['HTTP_RAW_POST_DATA'] = "<xml>
                <appid><![CDATA[wx7558e67c2d61eb8f]]></appid>
                <attach><![CDATA[e家洁在线支付]]></attach>
                <bank_type><![CDATA[CFT]]></bank_type>
                <cash_fee><![CDATA[1]]></cash_fee>
                <fee_type><![CDATA[CNY]]></fee_type>
                <is_subscribe><![CDATA[Y]]></is_subscribe>
                <mch_id><![CDATA[10037310]]></mch_id>
                <nonce_str><![CDATA[aoydf0e8u58c2scu2o441n1i5yxtxghr]]></nonce_str>
                <openid><![CDATA[o7Kvajh91Fmh_KYzhwX0LWZtpMPM]]></openid>
                <out_trade_no><![CDATA[15101922921]]></out_trade_no>
                <result_code><![CDATA[SUCCESS]]></result_code>
                <return_code><![CDATA[SUCCESS]]></return_code>
                <sign><![CDATA[3E437AF36D969693DD705034A8FFD5F9]]></sign>
                <time_end><![CDATA[20151019102921]]></time_end>
                <total_fee>1</total_fee>
                <trade_type><![CDATA[JSAPI]]></trade_type>
                <transaction_id><![CDATA[1004390062201510191251335932]]></transaction_id>
                </xml>";
            $post = array (
                "appid" => "wx7558e67c2d61eb8f",
                "attach" => "e家洁在线支付",
                "bank_type" => "CFT",
                "cash_fee" => "1",
                "fee_type" => "CNY",
                "is_subscribe" => "Y",
                "mch_id" => "10037310",
                "nonce_str" => "aoydf0e8u58c2scu2o441n1i5yxtxghr",
                "openid" => "o7Kvajh91Fmh_KYzhwX0LWZtpMPM",
                "out_trade_no" => "15101922921",
                "result_code" => "SUCCESS",
                "return_code" => "SUCCESS",
                "sign" => "3E437AF36D969693DD705034A8FFD5F9",
                "time_end" => "20151019102921",
                "total_fee" => "1",
                "trade_type" => "JSAPI",
                "transaction_id" => "1004390062201510191251335932"
            );
        }else{
            $post = json_decode(json_encode(simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        }

        //实例化模型
        $model = new GeneralPay();

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $model->toMoney($post['total_fee'],100,'/'),   //支付金额
            'general_pay_log_shop_name' => $post['attach'],   //商品名称
            'general_pay_log_eo_order_id' => $post['out_trade_no'],   //订单ID
            'general_pay_log_transaction_id' => $post['transaction_id'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['return_code']),   //支付状态
            'general_pay_log_status' => $post['return_code'],   //支付状态
            'pay_channel_id' => 10,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');



        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['out_trade_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        //验证支付结果
        if(!empty($model))
        {
            //验证签名
            //调用微信数据
            $class = new \wxjspay_class();
            $class->callback();
            $status = $class->notify();

            //签名验证成功
            if($status == 'SUCCESS')
            {
                $model->id = $GeneralPayId; //ID
                $model->general_pay_status = 1; //支付状态
                $model->general_pay_actual_money = $model->toMoney($post['total_fee'],100,'/');
                $model->general_pay_transaction_id = $post['transaction_id'];
                $model->general_pay_is_coupon = 1;
                $model->general_pay_eo_order_id = $post['out_trade_no'];
                $model->general_pay_verify = $model->makeSign();

                //commit
                $connection  = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try
                {
                    $model->save(false);
                    $attribute = $model->getAttributes();
                    if(!empty($model->order_id)){
                        //支付订单
                        GeneralPay::orderPay($attribute);
                    }else{
                        //充值支付
                        GeneralPay::pay($attribute);
                    }

                    $transaction->commit();

                    if(empty($data['debug'])){
                        //发送短信事件
                        $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                        $this->trigger('paySms');
                    }
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                }
            }
        }
    }

    /**
     * 支付宝APP回调
     * wx-js-notify
     */
    public function zhidahaoH5Notify($data)
    {
        if(!empty($data['debug'])){
            $post = [
                "order_no" => "15101980901",    //第三方的订单号
                "order_id" => "17600075",       //直达号中心订单号
                "sp_no" => "1049",              //商户号
                "pay_time" => "1445252232",     //支付时间
                "pay_result" => "1",            //1 支付成功,2 等待支付, 3 退款成功
                "paid_amount" => "1",           //成功支付现金金额(单位分)
                "coupons" => "0",               //优惠券使用金额(单位分)
                "promotion" => "0",             //立减金额(单位分)
                "sign" => "192efb9b70c26c4135d7550628f3e7cd"    //签名
            ];
            $_REQUEST = $post;
        }else{
            $post = $data;
        }

        //实例化模型
        $GeneralPayLogModel = new GeneralPayLog();

        //记录日志
        $dataLog = array(
            'general_pay_log_price' => $post['paid_amount'],   //支付金额
            'general_pay_log_shop_name' => '百度直达号支付',   //商品名称
            'general_pay_log_eo_order_id' => $post['order_no'],   //订单ID
            'general_pay_log_transaction_id' => $post['order_id'],   //交易流水号
            'general_pay_log_status_bool' => $GeneralPayLogModel->statusBool($post['pay_result']),   //支付状态
            'general_pay_log_status' => $post['pay_result'],   //支付状态
            'pay_channel_id' => 8,  //支付渠道ID
            'general_pay_log_json_aggregation' => json_encode($post),
            'data' => $post //文件数据
        );
        $this->on('insertLog',[$GeneralPayLogModel,'insertLog'],$dataLog);
        $this->trigger('insertLog');

        //实例化模型
        $model = new GeneralPay();

        //获取交易ID
        $GeneralPayId = $model->getGeneralPayId($post['order_no']);

        //查询支付记录
        $model = GeneralPay::find()->where(['id'=>$GeneralPayId,'general_pay_status'=>0])->one();

        //验证支付结果
        if(!empty($model))
        {
            //调用直达号数据
            $class = new \zhidahao_class();
            $status = $class->callback();


            //签名验证成功
            if( !empty($status) )
            {
                $model->id = $GeneralPayId; //ID
                $model->general_pay_status = 1; //支付状态
                $model->general_pay_actual_money = $model->toMoney($post['paid_amount'],100,'/');
                $model->general_pay_transaction_id = $post['order_id'];
                $model->general_pay_is_coupon = 1;
                $model->general_pay_eo_order_id = $post['order_no'];
                $model->general_pay_verify = $model->makeSign();

                //commit
                $connection  = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try
                {
                    $model->save(false);
                    $attribute = $model->getAttributes();

                    if(!empty($model->order_id)){
                        //支付订单
                        GeneralPay::orderPay($attribute);
                    }else{
                        //充值支付
                        GeneralPay::pay($attribute);
                    }

                    $transaction->commit();
                    echo $class->notify();
                    if(empty($data['debug'])){
                        //发送短信事件
                        $this->on("paySms",[new GeneralPay,'smsSend'],['customer_id'=>$model->customer_id,'order_id'=>$model->order_id]);
                        $this->trigger('paySms');
                    }
                }
                catch(Exception $e)
                {
                    $transaction->rollBack();
                }
            }
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
     * 退款分发
     * @param $order_id 订单ID
     * @param $customer_id  用户ID
     */
    public function requestRefund($order_id,$customer_id){
        //检查订单支付状态
        $condition['general_pay_status'] = 1;
        $condition['order_id'] = $order_id;
        $condition['customer_id'] = $customer_id;
        $condition['general_pay_mode'] = 1;
        $payData = GeneralPay::find()->where($condition)->asArray()->one();
        if( empty($payData) ){
            return false;
        }

        //退款分配
        $this->source($payData['general_pay_source']);
        $fun = $this->pay_type.'_refund';
        $this->$fun($payData);
        //交易记录
    }

    /**
     * 支付宝APPTUIK
     */
    private function alipay_app_refund(){}

    /**
     * 银联APP订单退款
     */
    private function up_app_refund(){}


    /**
     * 微信APP订单退款
     */
    private function wx_h5_refund($data){
        //['status'=>$status,'msg'=>$msg,'result'=>$result]
        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = 3;
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();
        dump($this->errors);
        //调用第三方退款

        //调用退余额

        //调用交易记录


    }


    /**
     * 微信APP订单退款
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
     * 微信APP订单查询
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
     * 直达号H5订单退款
     */
    private function zhifubaoH5Refund(){}


    /**
     * 百度APP订单退款
     */
    public function bfbAppRefund( $return_url,$sp_refund_no,$order_no,$cashback_amount )
    {

        $params = [
            'return_url' => $return_url,    //服务器异步通知地址
            'sp_refund_no' => $sp_refund_no,   //退款订单号
            'order_no' => $order_no,            //商户订单号
            'cashback_amount' => $cashback_amount,      //退款金额(单位/分)
        ];

        $class = new \bfbrefund_class();
        $data = $class->refund($params);
        return $data;

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
