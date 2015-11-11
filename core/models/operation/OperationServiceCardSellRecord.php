<?php

namespace core\models\operation;
use core\models\customer\Customer;
/**
 * This is the model class for table "ejj_operation_service_card_sell_record".
 *
 * @property string  $id
 * @property string  $service_card_sell_record_code
 * @property string  $customer_id
 * @property integer $customer_phone
 * @property string  $service_card_info_id
 * @property string  $service_card_info_name
 * @property string  $service_card_sell_record_money
 * @property integer $service_card_sell_record_channel_id
 * @property string  $service_card_sell_record_channel_name
 * @property integer $service_card_sell_record_status
 * @property integer $customer_trans_record_pay_mode
 * @property integer $pay_channel_id
 * @property string  $customer_trans_record_pay_channel
 * @property string  $customer_trans_record_transaction_id
 * @property string  $customer_trans_record_pay_money
 * @property integer $customer_trans_record_pay_account
 * @property integer $customer_trans_record_paid_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardSellRecord extends \dbbase\models\operation\OperationServiceCardSellRecord
{
    /**
     * @instruction 客户确定购买服务卡，产生购卡记录
     * @author zhangrenzhao
     * @date 2015-10-31
     * @param $attributes
     * 【   customer_id,用户ID
     *      service_card_info_id,卡信息ID
     *      service_card_sell_record_status，购卡订单状态
     *      service_card_sell_record_channel_id,购卡渠道ID
     *      service_card_sell_record_channel_name,购卡渠道名称
     *      service_card_sell_record_money,购卡订单金额 】
     * @return string
     */
    public function createServiceCardSellRecord($attributes)
    {   //0.创建一个对象
        $model=new OperationServiceCardSellRecord;
        if($attributes['customer_id']==null || $attributes['service_card_info_id']==null){
            return "error:customer_id or service_card_info_id is null";
        }
        //1.读取attributes，写入$model对象属性
        $model->setAttributes($attributes);

        //2.根据service_card_info_id查询服务卡信息
        $operationServiceCardInfo = OperationServiceCardInfo::getServiceCardInfoById($model->service_card_info_id);

        //3.写入服务卡信息
        if($operationServiceCardInfo!=null){
            //4.判断订单金额是否为NULL，若是，服务卡面值-优惠金额为订单金额(默认订单金额计算规则)
            if($model->service_card_sell_record_money==null){
                $service_card_sell_record_money=
                    $operationServiceCardInfo->service_card_info_value-$operationServiceCardInfo->service_card_info_rebate_value;

            }
            $model->setAttributes([
                'service_card_sell_record_money' => $service_card_sell_record_money,//订单金额
                'service_card_info_name' => $operationServiceCardInfo->service_card_info_name,//服务卡名
            ]);

        }else{
            $model->setAttributes([
                'service_card_sell_record_money' => 0,//订单金额
                'service_card_info_name' => "",//服务卡名
            ]);
        }

        //5.根据customer_id，获取用户对象
        $customer = Customer::getCustomerById($model->customer_id);

        //6.写入用户信息
        if($customer!=null){
            $model->setAttributes([
                'customer_phone' => $customer->customer_phone,
            ]);
        }

        //7.生成购卡销售订单号
        $service_card_sell_record_code=self::getServiceCardSellRecordCode();

        //8.初始化其他字段
        $model->setAttributes([
            'service_card_sell_record_code'=>$service_card_sell_record_code,
            'is_del' => 0,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);

        //9.保存购卡销售记录
        if($model->save()){
           return $model->service_card_sell_record_code;//返回购卡订单号
        }else{
            return $model->errors;//返回失败信息
        }
    }

    /**
     * @introduction 客户支付成功，回写支付信息
     * @author zhangrenzhao
     * @param $attributes
     * 【 service_card_sell_record_code,服务卡订单号
     *    customer_id,用户ID
     *   service_card_info_id,卡信息ID
     *    service_card_sell_record_status，购卡订单状态
     *    customer_trans_record_pay_mode，支付方式
     *    pay_channel_id，支付渠道ID
     *    customer_trans_record_pay_channel，支付渠道名称
     *    customer_trans_record_transaction_id，支付交易流水
     *    customer_trans_record_pay_money，支付金额
     *    customer_trans_record_pay_account,支付账户
     *    customer_trans_record_paid_at，支付时间
     * @return tool
     */
    public function backServiceCardSellRecord($attributes)
    {
        //0.基于销售订单号，查询订单信息.（以下操作是为满足现有交易实现方案）。
        $model=new OperationServiceCardSellRecord;
        if(!empty($attributes['service_card_sell_record_code'])){
            $model=OperationServiceCardSellRecord::getServiceCardSellRecordByCode($attributes['service_card_sell_record_code']);
        }
        //1.读取attributes，写入model对象属性
        $model->setAttributes($attributes);
        //2.设置更新时间
        $model->setAttributes([
            'updated_at'=>time(),
        ]);

        //3.保存回写信息
       if($model->save()){
           //回写成功，生成客户服务关系记录
           $attr_w=[
               "service_card_sell_record_id"=>$model->id,
               "customer_id"=>$model->customer_id,
               "customer_trans_record_pay_money"=>$model->customer_trans_record_pay_money,
               "service_card_with_customer_buy_at"=>$model->customer_trans_record_paid_at
           ];
          $service_card_with_customer_code=(new OperationServiceCardWithCustomer)->createServiceCardWithCustomer($attr_w);
           //在客服服务卡消费表中，初始化一条记录，记录服务卡消费的初始状态
           //组织服务卡消费记录参数
           $attrs= [
           "customer_id"=>$model->customer_id,//用户ID
            "service_card_with_customer_code"=>$service_card_with_customer_code,//服务卡号
           "service_card_consume_record_use_money"=>0,//使用金额
           "customer_trans_record_transaction_id"=>'0'//服务交易号
           ];
           //保存服务卡消费记录，并返回参数
          return ((new OperationServiceCardConsumeRecord)->createServiceCardConsumeRecord($attrs));
        }else{
            return $model->errors;//返回失败记录
       }
    }

    /**
     * @instruction 生成购卡订单号码
     * @return mixed
     */
    private function getServiceCardSellRecordCode(){
        $code="99999999";
        return $code;
    }

    /**
     * @instruction 根据ID获取购卡销售记录
     * @param $id
     */
    public static function getServiceCardSellRecordById($id){
        return self::findOne(['id'=>$id]);
    }

    /**
     * @introduction 逻辑删除
     * @return bool
     */
    public function softDelete()
    {
        $this->isdel = 1;
        return $this->save();
    }
    /**
     * @introduction基于购卡订单号，查询销售记录
     * @author zhangrenzhao
     * @date 2015-11-2
     * @param $service_card_sell_record_code
     * @return bool|string
     */
    public static function getServiceCardSellRecordByCode($service_card_sell_record_code)
    {
        $service_card_sell_record = self::findOne(['service_card_sell_record_code'=>$service_card_sell_record_code,
            'is_del'=>0]);
        return $service_card_sell_record;
    }

}
