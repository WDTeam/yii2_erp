<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%pay}}".
 *
 * @property integer $id
 * @property string $customer_id
 * @property string $order_id
 * @property string $pay_money
 * @property string $pay_actual_money
 * @property integer $pay_source
 * @property integer $pay_mode
 * @property integer $pay_status
 * @property string $pay_transaction_id
 * @property string $pay_eo_order_id
 * @property string $pay_memo
 * @property integer $pay_is_coupon
 * @property string $admin_id
 * @property string $worker_id
 * @property string $handle_admin_id
 * @property string $pay_verify
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_del
 */
class Pay extends \yii\db\ActiveRecord
{
    /**
     * @var 自定义属性
     */
    public $partner;
    private $pay_type;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id','pay_source_name'], 'required'],
            [['customer_id', 'order_id', 'pay_source', 'pay_mode', 'pay_status', 'pay_is_coupon', 'admin_id', 'worker_id', 'handle_admin_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['pay_money', 'pay_actual_money'], 'number'],
            [['pay_transaction_id'], 'string', 'max' => 40],
            [['pay_eo_order_id'], 'string', 'max' => 30],
            [['pay_memo'], 'string', 'max' => 255],
            [['pay_verify'], 'string', 'max' => 32],
            /**********以下自定义属性**********/
            [['partner'], 'required'],
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
            'pay'=>['pay_money','customer_id','partner','pay_source','pay_source_name'],
            //在线支付
            'online_pay'=>['pay_money','customer_id','partner','pay_source','pay_source_name','order_id'],
        ];
    }

    /**
     * 验证以后添加数据
     */
    public function afterValidate()
    {
        parent::afterValidate();
    }

    public function getPay(){
        call_user_func(__CLASS__ .'::'.$this->pay_type);
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
    public function source($source_id){
        $source = '';
        if(empty($source_id)) return $source;

        switch($source_id){
            case 1:
                $source = 'APP微信';
                $this->pay_type = 'wx_app';
                break;
            case 2:
                $source = 'H5微信';
                $this->pay_type = 'wx_h5';
                break;
            case 3:
                $source = 'APP百度钱包';
                $this->pay_type = 'bfb_app';
                break;
            case 4:
                $source = 'APP银联';
                $this->pay_type = 'up_app';
                break;
            case 5:
                $source = 'APP支付宝';
                $this->pay_type = 'alipay_app';
                break;
            case 6:
                $source = 'WEB支付宝';
                $this->pay_type = 'alipay_web';
                break;
            case 7:
                $source = 'HT淘宝';
                $this->pay_type = 'pay_ht';
                break;
            case 8:
                $source = 'H5百度直达号';
                $this->pay_type = 'zhidahao_h5_ht';
                break;
            case 9:
                $source = 'HT刷卡';
                $this->pay_type = 'pay_ht';
                break;
            case 10:
                $source = 'HT现金';
                $this->pay_type = 'pay_ht';
                break;
            default:
                $source = '';
        }
        return $source;
    }

    public function wx_app(){}
    public function wx_h5(){}
    public function bfb_app(){}
    public function up_app(){}
    public function alipay_app(){}


    /**
     * 支付宝WEB
     */
    private function alipay_web()
    {
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = 'a_w_'.$this->id;

        //订单名称
        $subject = 'WIDsubject';

        //订单描述
        $body = 'WIDbody';

        $alipay_config = Yii::$app->params['alipay_web_config'];

        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipay_config['partner']),
            "seller_email" => trim($alipay_config['seller_email']),
            "payment_type"	=> 1,
            "notify_url"	=> $alipay_config['notify_url'],
            "return_url"	=> $alipay_config['return_url'],
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $subject,
            "total_fee"	=> $this->pay_money,
            "body"	=> $body,
            "show_url"	=> '',
            "anti_phishing_key"	=> time(),
            "exter_invoke_ip"	=> $_SERVER['REMOTE_ADDR'],
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        var_dump($parameter);exit;
        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);

        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
        echo $html_text;
    }
    public function pay_htC(){}
    public function zhidahao_h5_ht(){}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_id' => Yii::t('app', '用户ID'),
            'order_id' => Yii::t('app', '订单ID'),
            'pay_money' => Yii::t('app', '发起充值/交易金额'),
            'pay_actual_money' => Yii::t('app', '实际充值/交易金额'),
            'pay_source' => Yii::t('app', '数据来源:1=APP微信,2=H5微信,3=APP百度钱包,4=APP银联,5=APP支付宝,6=WEB支付宝,7=HT淘宝,8=H5百度直达号,9=HT刷卡,10=HT现金,11=HT刷卡'),
            'pay_source_name' => Yii::t('app', '来源名称'),
            'pay_mode' => Yii::t('app', '交易方式:1=充值,2=余额支付,3=在线支付,4=退款,5=赔偿'),
            'pay_status' => Yii::t('app', '状态：0=失败,1=成功'),
            'pay_transaction_id' => Yii::t('app', '第三方交易流水号'),
            'pay_eo_order_id' => Yii::t('app', '商户ID(第三方交易)'),
            'pay_memo' => Yii::t('app', '备注'),
            'pay_is_coupon' => Yii::t('app', '是否返券'),
            'admin_id' => Yii::t('app', '管理员ID'),
            'worker_id' => Yii::t('app', '销售卡阿姨ID'),
            'handle_admin_id' => Yii::t('app', '办卡人ID'),
            'pay_verify' => Yii::t('app', '支付验证'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_del' => Yii::t('app', '删除'),
        ];
    }
}
