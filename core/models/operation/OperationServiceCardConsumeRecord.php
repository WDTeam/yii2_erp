<?php

namespace core\models\operation;

use OperationServiceCardWithCustomer;

/**
 * This is the model class for table "ejj_operation_service_card_consume_record".
 *
 * @property string  $id
 * @property string  $customer_id
 * @property string  $customer_trans_record_transaction_id
 * @property string  $order_id
 * @property string  $order_code
 * @property string  $service_card_with_customer _id
 * @property string  $service_card_with_customer_code
 * @property string  $service_card_consume_record_front_money
 * @property string  $service_card_consume_record_behind_money
 * @property integer $service_card_consume_record_consume_type
 * @property integer $service_card_consume_record_business_type
 * @property string  $service_card_consume_record_use_money
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class OperationServiceCardConsumeRecord extends \common\models\operation\OperationServiceCardConsumeRecord
{
    /**
     * save service card consume record
     * @author zhangrenzhao
     * @param $attributes
     * 【   order_id, 订单ID
     *      order_code,订单号
     *      customer_id,用户ID
     *      customer_trans_record_transaction_id,交易流水
     *      service_card_with_customer_id,客户服务卡ID
     *      service_card_with_customer_code,客户服务卡号
     *      service_card_consume_record_consume_type,消费类型
     *      service_card_consume_record_business_type,业务类型
     *      service_card_consume_record_use_money，使用金额 】
     * @return bool
     */
    public function createServiceCardConsumeRecord($attributes)
    {
        /**
         *读取attributes，并写入this对象属性
         */
        $this->setAttributes($attributes);
        /**
         * 根据service_card_with_customer_id查询客服服务卡信息，计算服务卡使用后余额
         */
        $operationServiceCardWithCustomer = OperationServiceCardWithCustomer::getServiceCardWithCustomerById($this->service_card_with_customer_id);
        //写入使用前金额
        $this->setAttributes([
            'service_card_consume_record_front_money' => $operationServiceCardWithCustomer->service_card_with_customer_balance,
        ]);
        //计算使用后金额
        $this->service_card_consume_record_behind_money = $this->service_card_with_customer_balance - $this->service_card_consume_record_use_money;
        //写入使用后金额
        $this->setAttributes([
            'service_card_consume_record_behind_money' => $this->service_card_consume_record_behind_money,
        ]);
        /**
         * 设置客户服务卡其他字段
         */
        $this->setAttributes([
            'isdel' => 0,//逻辑删除
        ]);
        /**
         * 服务卡消费记录保存成功，更新客户服务卡余额
         */
        if($this->doSave()){
            //更新服务卡余额
            OperationServiceCardWithCustomer::updateServiceCardWithCustomerBalanceById($this->service_card_with_customer_id,$this->service_card_consume_record_behind_money);
            return true;
        }else{
            return false;
        }

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
