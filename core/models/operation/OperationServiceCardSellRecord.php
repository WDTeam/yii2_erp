<?php

namespace core\models\operation;
use OperationServiceCardInfo;
use OperationServiceCardWithCustomer;
use core\models\customer\Customer;
/**
 * This is the model class for table "ejj_operation_service_card_sell_record".
 *
 * @property string $id
 * @property string $service_card_sell_record_code
 * @property string $customer_id
 * @property integer $customer_phone
 * @property string $service_card_info_id
 * @property string $service_card_info_name
 * @property string $service_card_sell_record_money
 * @property integer $service_card_sell_record_channel_id
 * @property string $service_card_sell_record_channel_name
 * @property integer $service_card_sell_record_status
 * @property integer $customer_trans_record_pay_mode
 * @property integer $pay_channel_id
 * @property string $customer_trans_record_pay_channel
 * @property string $customer_trans_record_transaction_id
 * @property string $customer_trans_record_pay_money
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
     * @param $attributes
     * 【   customer_id,用户ID
     *      server_card_info_id,卡信息ID
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

        //2.根据server_card_info_id查询服务卡信息
        $operationServiceCardInfo = OperationServiceCardInfo::getServiceCardInfoById($this->server_card_info_id);

        //3.写入服务卡信息
        $this->setAttributes([
            'service_card_info_name' => $operationServiceCardInfo->service_card_info_name,//服务卡名
        ]);

        //4.根据customer_id，获取用户对象
        $customer = Customer::getCustomerById($this->customer_id);

        //5.写入用户信息
        $this->setAttributes([
            'order_customer_phone' => $customer->customer_phone,
        ]);

        //6.生成购卡销售订单号
        $service_card_sell_record_code=self::getService_card_sell_record_code();

        //7.初始化其他字段
        $this->setAttributes([
            'service_card_sell_record_code'=>$service_card_sell_record_code,
            'is_del' => 0,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);

        //8.保存购卡销售记录
        if($this->doSave()){
            return $this->primaryKey;//返回购卡订单ID
        }else{
            return null;//返回NULL，说明保存失败
        }
    }

    /**
     * @introduction 客户支付成功，回写支付信息
     * @param $attributes
     * 【 id,服务卡订单记录
     *    customer_id,用户ID
     *    server_card_info_id,卡信息ID
     *    service_card_sell_record_status，购卡订单状态
     *    customer_trans_record_pay_mode，支付方式
     *    pay_channel_id，支付渠道ID
     *    customer_trans_record_pay_channel，支付渠道名称
     *    customer_trans_record_transaction_id，支付交易流水
     *    customer_trans_record_pay_money，支付金额
     *    customer_trans_record_pay_account,支付账户
     *    customer_trans_record _paid_at，支付时间
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
        //3.保存回写信息
        if($this->dosave()){
            //回写成功，生成客户服务关系记录
           return OperationServiceCardWithCustomer::createServiceCardWithCustomer(
               $this->id,
               $this->customer_id,
               $this->customer_trans_record_pay_money,
               $this->customer_trans_record_paid_at);

        }else{
            return null;//NULL表示数据生成失败
        }
    }

    /**
     * @instruction 生成购卡订单号码
     * @return mixed
     */
    private function getService_card_sell_record_code(){
        $code='99999999';
        return code;
    }

    /**
     * @instruction 根据ID获取购卡销售记录
     * @param $id
     */
    public function getServiceCardSellRecordById($id){
        return self::findOne(['id'=>$id]);
    }
    /**
     * 软删除
     */
    public function softDelete()
    {
        $this->isdel = 1;
        return $this->save();
    }
}
