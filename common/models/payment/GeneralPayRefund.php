<?php
namespace common\models\payment;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use core\models\customer\CustomerTransRecord;
use core\models\order\OrderSearch;

class GeneralPayRefund extends GeneralPayCommon
{

    /**
     * 分配退款渠道
     * 交易方式:1=消费,2=充值,3=退款,4=补偿
     * @param $order_id 订单ID
     * @param $customer_id  用户ID
     */
    public function call_pay_refund($order_id,$customer_id){
        //检查订单支付状态
        $condition['general_pay_status'] = 1;
        $condition['order_id'] = $order_id;
        $condition['customer_id'] = $customer_id;
        $condition['general_pay_mode'] = 1;
        $payData = GeneralPay::find()->where($condition)->asArray()->one();

        //检查退款状态
        $condition['general_pay_mode'] = 3;
        $refundData = GeneralPay::find()->where($condition)->asArray()->one();

        //假如没有支付数据 || 已经有过退款数据
        if( empty($payData) || !empty($refundData) ){
            return false;
        }

        //分配退款渠道
        $this->source($payData['general_pay_source']);
        $fun = $this->pay_type;
        return $this->$fun($payData);
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
                $this->pay_type = 'wx_app_refund';
                break;
            case 2:
                $this->pay_type = 'wx_h5_refund';
                break;
            case 3:
                $this->pay_type = 'bfb_app_refund';
                break;
            case 4:
                $this->pay_type = 'up_app_refund';
                break;
            case 5:
                $this->pay_type = 'alipay_app_refund';
                break;
            case 6:
                $this->pay_type = 'alipay_web_refund';
                break;
            case 7:
                $this->pay_type = 'zhidahao_h5_refund';
                break;
            case 20:
                $this->pay_type = 'pay_ht_refund';
                break;
            case 21:
                $this->pay_type = 'weibo_h5_refund';
                break;
        }
        return $source;
    }

    /**
     * 微信APP订单退款
     */
    private function wx_app_refund($data){
        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $data['general_pay_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            GeneralPayCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 微信H5订单退款
     */
    private function wx_h5_refund($data){
        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $data['general_pay_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            GeneralPayCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);

    }

    /**
     * 百度钱包APP订单退款
     */
    private function bfb_app_refund($data)
    {

        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $data['general_pay_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            GeneralPayCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 银联APP订单退款
     */
    private function up_app_refund($data){
        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $data['general_pay_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            GeneralPayCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 支付宝APP订单退款
     */
    private function alipay_app_refund($data){
        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $data['general_pay_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            GeneralPayCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 支付宝WEB订单退款
     */
    private function alipay_web_refund($data){
        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $data['general_pay_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            GeneralPayCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 百度直达号APP订单退款
     */
    private function zhidahao_h5_refund($data){
        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $data['general_pay_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            GeneralPayCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 后台订单退款
     */
    private function pay_ht_refund($data){
        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $data['general_pay_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            GeneralPayCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }


    /**
     * 微博支付H5订单退款
     */
    private function weibo_h5_refund($data){
        //创建退款交易
        $data['general_pay_mode'] = 3;
        $data['general_pay_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['general_pay_status'] = 1;
        $data['general_pay_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            GeneralPayCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
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
    /*
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
    */

    /**
     * 微信APP订单查询
     * @param int $out_trade_no     支付订单号
     * @param int $transaction_id   支付交易流水号
     * @param int $out_refund_no    退款交易流水号
     * @param int $refund_id        退款订单号
     */
    /*
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
    */

    /**
     * 百度APP订单退款
     */
    /*
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
