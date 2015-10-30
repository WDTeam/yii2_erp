<?php
namespace dbbase\models\payment;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use core\models\customer\CustomerTransRecord;
use core\models\order\OrderSearch;

class PaymentRefund extends PaymentCommon
{

    /**
     * 分配退款渠道
     * 交易方式:1=消费,2=充值,3=退款,4=补偿
     * @param $order_id 订单ID
     * @param $customer_id  用户ID
     */
    public function call_pay_refund($order_id,$customer_id){
        //检查在线支付订单支付状态
        $condition['payment_status'] = 1;
        $condition['order_id'] = $order_id;
        $condition['customer_id'] = $customer_id;
        $condition['payment_mode'] = 1;
        $payData = Payment::find()->where($condition)->asArray()->one();
        //判断线上是否支付过,如果没有支付过,查看订单是否产生过
        if(!empty($payData)){
            //检查退款状态
            $condition['payment_mode'] = 3;
            $refundData = Payment::find()->where($condition)->asArray()->one();
        }else{
            $payData = $this->orderInfo($order_id)->getAttributes();
            $payData['order_id'] = $order_id;
            $payData['customer_id'] = $customer_id;
        }

        //假如没有支付数据 || 已经有过退款数据
        if( empty($payData) || !empty($refundData) ){
            return false;
        }

        //分配退款渠道
        $this->source($payData['payment_source']);
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
        $data['payment_mode'] = 3;
        $data['payment_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['payment_status'] = 1;
        $data['payment_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            PaymentCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 微信H5订单退款
     */
    private function wx_h5_refund($data){
        //创建退款交易
        $data['payment_mode'] = 3;
        $data['payment_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['payment_status'] = 1;
        $data['payment_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            PaymentCommon::orderRefund($data);
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
        $data['payment_mode'] = 3;
        $data['payment_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['payment_status'] = 1;
        $data['payment_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            PaymentCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 银联APP订单退款
     */
    private function up_app_refund($data){
        //创建退款交易
        $data['payment_mode'] = 3;
        $data['payment_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['payment_status'] = 1;
        $data['payment_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            PaymentCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 支付宝APP订单退款
     */
    private function alipay_app_refund($data){
        //创建退款交易
        $data['payment_mode'] = 3;
        $data['payment_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['payment_status'] = 1;
        $data['payment_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            PaymentCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 支付宝WEB订单退款
     */
    private function alipay_web_refund($data){
        //创建退款交易
        $data['payment_mode'] = 3;
        $data['payment_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['payment_status'] = 1;
        $data['payment_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            PaymentCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 百度直达号APP订单退款
     */
    private function zhidahao_h5_refund($data){
        //创建退款交易
        $data['payment_mode'] = 3;
        $data['payment_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['payment_status'] = 1;
        $data['payment_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            PaymentCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }

    /**
     * 后台订单退款
     */
    private function pay_ht_refund($data){

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            PaymentCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
    }


    /**
     * 微博支付H5订单退款
     */
    private function weibo_h5_refund($data){
        //创建退款交易
        $data['payment_mode'] = 3;
        $data['payment_eo_order_id'] = $this->create_out_trade_no(2);
        $data['is_reconciliation'] = 0;
        $data['payment_status'] = 1;
        $data['payment_transaction_id'] = 0;
        $this->scenario = 'refund';
        $this->attributes = $data;
        $this->insert();

        //调用第三方退款

        //调用退余额
        if( !empty($data['order_id']) && !empty($data['customer_id'])){
            PaymentCommon::orderRefund($data);
        }

        //调用交易记录
        return CustomerTransRecord::refundRecord($data);
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
            'payment_source' => Yii::t('app', '数据来源:1=APP微信,2=H5微信,3=APP百度钱包,4=APP银联,5=APP支付宝,6=WEB支付宝,7=HT淘宝,8=H5百度直达号,9=HT刷卡,10=HT现金,11=HT刷卡'),
            'payment_source_name' => Yii::t('app', '数据来源名称'),
            'payment_mode' => Yii::t('app', '交易方式:1=充值,2=余额支付,3=在线支付,4=退款,5=赔偿'),
            'payment_status' => Yii::t('app', '状态：0=失败,1=成功'),
            'payment_transaction_id' => Yii::t('app', '第三方交易流水号'),
            'payment_eo_order_id' => Yii::t('app', '商户ID(第三方交易)'),
            'payment_memo' => Yii::t('app', '备注'),
            'payment_is_coupon' => Yii::t('app', '是否返券'),
            'admin_id' => Yii::t('app', '管理员ID'),
            'payment_admin_name' => Yii::t('app', '管理员名称'),
            'worker_id' => Yii::t('app', '销售卡阿姨ID'),
            'handle_admin_id' => Yii::t('app', '办卡人ID'),
            'payment_handle_admin_id' => Yii::t('app', '办卡人名称'),
            'payment_verify' => Yii::t('app', '支付验证'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '更新时间'),
            'is_reconciliation' => Yii::t('app', '是否对账'),
        ];
    }

}
