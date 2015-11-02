<?php

namespace core\models\operation;

use OperationServiceCardSellRecord;
use OperationServiceCardInfo;
use core\models\customer\Customer;


/**
 * This is the model class for table "ejj_operation_service_card_with_customer".
 *
 * @property string $id
 * @property string $service_card_sell_record_id
 * @property string $service_card_sell_record_code
 * @property string $server_card_info_id
 * @property string $service_card_with_customer_code
 * @property string $server_card_info_name
 * @property string $customer_trans_record_pay_money
 * @property string $server_card_info_value
 * @property string $service_card_info_rebate_value
 * @property string $service_card_with_customer_balance
 * @property integer $customer_id
 * @property string  $customer_phone
 * @property integer $server_card_info_scope
 * @property integer $service_card_with_customer_buy_at
 * @property integer $service_card_with_customer_valid_at
 * @property integer $service_card_with_customer_activated_at
 * @property integer $service_card_with_customer_status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardWithCustomer extends \dbbase\models\operation\OperationServiceCardWithCustomer
{
    /**
     * @introduction 根据客户服务卡ID，查询服务卡信息
     * @param $id
     * @return null|static
     */
    public static function getServiceCardWithCustomerById($id) {
        return self::findOne(['id'=>$id]);
    }
    /**
     * @introduction 根据服务卡ID，更新客户服务卡余额
     * @param $id
     * @param $service_card_with_customer_balance
     * @return bool
     */
    public function updateServiceCardWithCustomerBalanceById($id, $service_card_with_customer_balance)
    {
        $this->id = $id;
        $this->service_card_with_customer_balance = $service_card_with_customer_balance;
        if($this->save()){
            return true;
        }
        return false;
    }
    /**
     * @introduction 支付成功，生成客户服务卡关系记录
     * @author zhangrenzhao
     * @date 2015-10-30
     * @param【
     *      $service_card_sell_record_id,购物卡销售记录ID
     *      $customer_id,用户ID
     *      $customer_trans_record_pay_money,支付金额
     *      $service_card_with_customer_buy_at，支付时间】
     * @return mixed|null
     */
    public function createServiceCardWithCustomer($service_card_sell_record_id,$customer_id,$customer_trans_record_pay_money,$service_card_with_customer_buy_at)
    {
        //1.根据customer_id，获取用户对象
        $customer = Customer::getCustomerById($this->customer_id);

        //2.写入用户信息
        $this->setAttributes([
            'order_customer_phone' => $customer->customer_phone,
        ]);

        //3.获取购卡销售订单信息
        $operationServiceCardSellRecord=OperationServiceCardSellRecord::getServiceCardSellRecordById($service_card_sell_record_id);

        //4.写入购卡销售订单信息
        $this->setAttributes([
            'service_card_sell_record_code' => $operationServiceCardSellRecord->service_card_sell_record_code,
            'server_card_info_id'=>$operationServiceCardSellRecord->server_card_info_id,
        ]);

        //5.获取服务卡信息
        $operationServiceCardInfo=OperationServiceCardInfo::getServiceCardInfo($operationServiceCardSellRecord->server_card_info_id);

        //6.写入服务卡信息
        $this->setAttributes([
            'server_card_info_name' =>  $operationServiceCardInfo->server_card_info_name,
            'server_card_info_value'=> $operationServiceCardInfo->server_card_info_value,
            'server_card_info_scope'=> $operationServiceCardInfo->server_card_info_scope,
            'service_card_info_rebate_value'=> $operationServiceCardInfo->service_card_info_rebate_value,
        ]);

        //7.生成服务卡号
        $service_card_with_customer_code=self::getServiceCardWithCustomerCode();

        //计算有效截至日期

        $service_card_with_customer_valid_at=strtotime()+$operationServiceCardInfo->service_card_info_valid_days;

        //8.初始化其他字段
        $this->setAttributes([
            'is_del' => 0,
            'created_at'=>time(),
            'updated_at'=>time(),
            'customer_trans_record_pay_money'=>$customer_trans_record_pay_money,
            'service_card_with_customer_buy_at'=>$service_card_with_customer_buy_at,
            'customer_id'=>$customer_id,
            'service_card_sell_record_id'=>$service_card_sell_record_id,
            'service_card_with_customer_activated_at'=>null,
            'service_card_with_customer_status'=>0,
            'service_card_with_customer_code'=>$service_card_with_customer_code,
            'service_card_with_customer_valid_at'=>$service_card_with_customer_valid_at,
            'service_card_with_customer_balance'=>$operationServiceCardInfo->server_card_info_value,//服务卡创建时，余额等于面值
        ]);
        //9.保存客户服务卡关系记录
        if($this->doSave()){
            $attributes= ['service_card_with_customer_id' => $this->primaryKey,
                'service_card_with_customer_code' =>$this->service_card_sell_record_code,
                'server_card_info_value' =>$this->server_card_info_value,
                 ];
            return $attributes;//返回卡信息

        }else{
            return null;//返回NULL，说明保存失败
        }
    }

    /**
     * @intruction 生成服务卡号
     * @return string
     */
    private function getServiceCardWithCustomerCode(){
        $code='88888888';
        return $code;
    }

    /**
     * @intruction 查询用户下所有服务卡信息
     * @param $customer_id
     * @return static[]
     */
    public static function getServiceCardWithCustomerByCustomerId($customer_id){
        return self::findAll(['customer_id'=>$customer_id]);
    }
    /**
     * 软删除
     */
    public function softDelete()
    {
        $this->isdel = 1;
        return $this->save();
    }

    /**
     * @introduction 基于服务卡号查询余额
     * @author zhangrenzhao
     * @param $service_card_with_customer_code
     * @return bool|string
     */
    public function getServiceCardWithCustomerBalanceByCode($service_card_with_customer_code)
    {
        $service_card_with_customer_balance = self::find()
            ->select(['service_card_with_customer_balance'])
            ->where(['service_card_with_customer_code'=>$this->$service_card_with_customer_code,
                     'is_del'=>0])->scalar();
        return $service_card_with_customer_balance;
    }
}
