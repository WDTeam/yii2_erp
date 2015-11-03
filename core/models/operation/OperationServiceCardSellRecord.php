<?php

namespace core\models\operation;
use core\models\operation\OperationServiceCardInfo;
use core\models\operation\OperationServiceCardWithCustomer;
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
    {
        //1.读取attributes，写入this对象属性
        $this->setAttributes($attributes);
        //2.根据service_card_info_id查询服务卡信息
        $operationServiceCardInfo = OperationServiceCardInfo::getServiceCardInfoById($this->service_card_info_id);
        //3.写入服务卡信息
        $this->setAttributes([
            'service_card_info_name' => $operationServiceCardInfo->service_card_info_name,//服务卡名
        ]);
        //4.判断订单金额是否为NULL，若是，服务卡面值-优惠金额为订单金额
        if($this->service_card_sell_record_money==null){
                $service_card_sell_record_money=
                $operationServiceCardInfo->service_card_info_value-$operationServiceCardInfo->service_card_info_rebate_value;
            $this->setAttributes([
                'service_card_sell_record_money' => $service_card_sell_record_money//服务卡名
            ]);
        }

        //5.根据customer_id，获取用户对象
        $customer = Customer::getCustomerById($this->customer_id);

        //6.写入用户信息
        $this->setAttributes([
            'customer_phone' => $customer->customer_phone,
        ]);
        //7.生成购卡销售订单号
        $service_card_sell_record_code=self::getServiceCardSellRecordCode();

        //8.初始化其他字段
        $this->setAttributes([
            'service_card_sell_record_code'=>$service_card_sell_record_code,
            'is_del' => 0,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);
        //9.保存购卡销售记录
        if($this->doSave()){
            return $this->service_card_sell_record_code;//返回购卡订单号
        }else{
            return null;//返回NULL，说明保存失败
        }
    }

    /**
     * @introduction 客户支付成功，回写支付信息
     * @author zhangrenzhao
     * @param $attributes
     * 【 service_card_sell_record_code,服务卡订单号
     *    customer_id,用户ID
     *    server_card_info_id,卡信息ID
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
        //1.读取attributes，写入this对象属性
        $this->setAttributes($attributes);
        //2.设置更新时间
        $this->setAttributes([
            'updated_at'=>time(),
        ]);
        //3.基于code查询id，目的是以主键id更新记录。
        //以上操作为满足现有交易流水，业务需确定一个code一条记录。
        $tempO=$this->getServiceCardSellRecordByCode($this->service_card_sell_record_code);
        $this->setAttributes([
            'id' =>$tempO->id,
        ]);
        //3.保存回写信息
        if($this->dosave()){
            //回写成功，生成客户服务关系记录
               return (new OperationServiceCardWithCustomer)->createServiceCardWithCustomer(
               $this->id,
               $this->customer_id,
               $this->customer_trans_record_pay_money,
               $this->customer_trans_record_paid_at);

            return true;
        }else{
            return null;//NULL表示数据生成失败
        }
    }

    /**
     * @instruction 生成购卡订单号码
     * @return mixed
     */
    private function getServiceCardSellRecordCode(){
        $code='9999'+time();
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
    public function getServiceCardSellRecordByCode($service_card_sell_record_code)
    {
        $service_card_sell_record = self::findOne(['service_card_sell_record_code'=>$service_card_sell_record_code,
            'is_del'=>0]);
        return $service_card_sell_record;
    }

}
