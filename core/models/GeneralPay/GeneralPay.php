<?php

namespace core\models\GeneralPay;

use core\models\CustomerTransRecord\CustomerTransRecord;
use core\models\Customer;
use Yii;

class GeneralPay extends \common\models\GeneralPay
{
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
            [['customer_id', 'general_pay_source_name'], 'required'],
            [['customer_id', 'order_id', 'general_pay_source', 'general_pay_mode', 'general_pay_status', 'general_pay_is_coupon', 'admin_id', 'worker_id', 'handle_admin_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['general_pay_money', 'general_pay_actual_money'], 'number'],
            [['general_pay_source_name'], 'string', 'max' => 20],
            [['general_pay_transaction_id'], 'string', 'max' => 40],
            [['general_pay_eo_order_id', 'general_pay_admin_name', 'general_pay_handle_admin_id'], 'string', 'max' => 30],
            [['general_pay_memo'], 'string', 'max' => 255],
            [['general_pay_verify'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('GeneralPay', 'ID'),
            'customer_id' => Yii::t('GeneralPay', '用户ID'),
            'order_id' => Yii::t('GeneralPay', '订单ID'),
            'general_pay_money' => Yii::t('GeneralPay', '发起充值/交易金额'),
            'general_pay_actual_money' => Yii::t('GeneralPay', '实际充值/交易金额'),
            'general_pay_source' => Yii::t('GeneralPay', '数据来源:1=APP微信,2=H5微信,3=APP百度钱包,4=APP银联,5=APP支付宝,6=WEB支付宝,7=HT淘宝,8=H5百度直达号,9=HT刷卡,10=HT现金,11=HT刷卡'),
            'general_pay_source_name' => Yii::t('GeneralPay', '数据来源名称'),
            'general_pay_mode' => Yii::t('GeneralPay', '交易方式:1=充值,2=余额支付,3=在线支付,4=退款,5=赔偿'),
            'general_pay_status' => Yii::t('GeneralPay', '状态：0=失败,1=成功'),
            'general_pay_transaction_id' => Yii::t('GeneralPay', '第三方交易流水号'),
            'general_pay_eo_order_id' => Yii::t('GeneralPay', '商户ID(第三方交易)'),
            'general_pay_memo' => Yii::t('GeneralPay', '备注'),
            'general_pay_is_coupon' => Yii::t('GeneralPay', '是否返券'),
            'admin_id' => Yii::t('GeneralPay', '管理员ID'),
            'general_pay_admin_name' => Yii::t('GeneralPay', '管理员名称'),
            'worker_id' => Yii::t('GeneralPay', '销售卡阿姨ID'),
            'handle_admin_id' => Yii::t('GeneralPay', '办卡人ID'),
            'general_pay_handle_admin_id' => Yii::t('GeneralPay', '办卡人名称'),
            'general_pay_verify' => Yii::t('GeneralPay', '支付验证'),
            'created_at' => Yii::t('GeneralPay', '创建时间'),
            'updated_at' => Yii::t('GeneralPay', '更新时间'),
            'is_del' => Yii::t('GeneralPay', '删除'),
        ];
    }

    /**
     * @inheritdoc
     * @return GeneralPayQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GeneralPayQuery(get_called_class());
    }

    /**
     * @param $condition
     * @param $fileds
     * @return array|GeneralPay|null
     */
    public static function getGeneralPayByInfo($condition,$fileds = '*')
    {
        return GeneralPay::find()->select($fileds)->where($condition)->asArray()->one();
    }

    /**
     * 服务卡支付
     * @param $data  订单数据
     */
    public static function serviceCradPay($data)
    {
        try{
            //用户服务卡扣款
            //ServiceCard();
            //用户交易记录
            CustomerTransRecord::analysisRecord($data);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * 余额支付
     * @param $data  订单数据
     */
    public static function balancePay($data)
    {
        try{
            //用户服务卡扣款
            Customer::decBalance($data['customer_id'],$data['general_pay_actual_money']);
            //用户交易记录
            CustomerTransRecord::analysisRecord($data);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * 现金支付
     * @param $data 订单数据
     */
    public static function cashPay($data)
    {
        try{
            //用户交易记录
            CustomerTransRecord::analysisRecord($data);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * 预付费
     * @param $data 订单数据
     */
    public function perPay($data)
    {
        try{
            //用户交易记录
            CustomerTransRecord::analysisRecord($data);
            return true;
        } catch(Exception $e) {
            return false;
        }
    }


    /**
     * 调用(调起)在线支付,发送给支付接口的数据
     * @param integer $pay_money 支付金额
     * @param integer $customer_id 消费者ID
     * @param integer $channel_id 渠道ID
     * @param integer $order_id 订单ID
     * @param integer $partner 第三方合作号
     */
    public static function getPayParams( $pay_money,$customer_id,$channel_id,$partner,$order_id=0,$ext_params=[] )
    {
        $data = [
            "pay_money" => $pay_money,
            "customer_id" => $customer_id,
            "general_pay_source" => $channel_id,
            "partner" => $partner,
            "order_id" => $order_id
        ];
        $data = array_merge($data,$ext_params);
        //实例化模型
        $model = new GeneralPay();

        //查询订单是否已经支付过
        if( !empty($data['order_id']) )
        {
            $order = parent::getPayStatus($data['order_id'],1);
            if(!empty($order))
            {
                return ['status'=>0 , 'info'=>'订单已经支付过', 'data'=>''];
            }
        }

        //在线支付（online_pay），在线充值（pay）
        if(empty($data['order_id']))
        {
            if($channel_id == '2'){
                $scenario = 'wx_h5_pay';
            }elseif($channel_id == '7'){
                $scenario = 'zhidahao_h5_pay';
            }else{
                $scenario = 'pay';
            }
            //交易方式
            $data['general_pay_mode'] = 1;//充值
        }
        else
        {
            if($channel_id == '2'){
                $scenario = 'wx_h5_online_pay';
            }elseif($channel_id == '7'){
                $scenario = 'zhidahao_h5_online_pay';
            }else{
                $scenario = 'online_pay';
            }
            //交易方式
            $data['general_pay_mode'] = 3;//在线支付
        }

        //支付来源,定义分发支付渠道
        $data['general_pay_source_name'] = $model->source($data['general_pay_source']);

        //使用场景
        $model->scenario = $scenario;
        $model->attributes = $data;

        //验证数据
        if( $model->validate() && $model->save() )
        {
            //返回组装数据
            return $model->call_pay();
        }
        else
        {
            return ['status'=>0 , 'info'=>'数据返回失败', 'data'=>$model->errors];
        }

    }


    /**
     * 银联支付回调
     * @param $data
     */
    public function upAppNotify($data){
        parent::upAppNotify($data);
    }

    /**
     * 支付宝APP回调
     * @param $data
     */
    public function alipayAppNotify($data){
        parent::alipayAppNotify($data);
    }

    /**
     * 微信APP回调
     * @param $data
     */
    public function wxAppNotify($data){
        parent::wxAppNotify($data);
    }

    /**
     * 百付宝APP回调
     * @param $data
     */
    public function bfbAppNotify($data){
        parent::bfbAppNotify($data);
    }

}
