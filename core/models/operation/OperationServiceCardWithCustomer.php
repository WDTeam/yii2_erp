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
 * @property string $customer_phone
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
     * @param $id
     * @return null|static
     * ���ݿͻ�����ID����ѯ������Ϣ
     */
    public static function getServiceCardWithCustomerById($id) {
        return self::findOne(['id'=>$id]);
    }
    /**
     * @param $id
     * @param $service_card_with_customer_balance
     * @return bool
     * ���ݷ���ID�����¿ͻ��������
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
     * @introduction ֧���ɹ������ɿͻ����񿨹�ϵ��¼
     * @param
     * $service_card_sell_record_id,���￨���ۼ�¼ID
     * $customer_id,�û�ID
     * $customer_trans_record_pay_money,֧�����
     * $service_card_with_customer_buy_at��֧��ʱ��
     * @return mixed|null
     */
    public function createServiceCardWithCustomer($service_card_sell_record_id,$customer_id,$customer_trans_record_pay_money,$service_card_with_customer_buy_at)
    {
        //1.����customer_id����ȡ�û�����
        $customer = Customer::getCustomerById($this->customer_id);

        //2.д���û���Ϣ
        $this->setAttributes([
            'order_customer_phone' => $customer->customer_phone,
        ]);

        //3.��ȡ�������۶�����Ϣ
        $operationServiceCardSellRecord=OperationServiceCardSellRecord::getServiceCardSellRecordById($service_card_sell_record_id);

        //4.д�빺�����۶�����Ϣ
        $this->setAttributes([
            'service_card_sell_record_code' => $operationServiceCardSellRecord->service_card_sell_record_code,
            'server_card_info_id'=>$operationServiceCardSellRecord->server_card_info_id,
        ]);

        //5.��ȡ������Ϣ
        $operationServiceCardInfo=OperationServiceCardInfo::getServiceCardInfo($operationServiceCardSellRecord->server_card_info_id);

        //6.д�������Ϣ
        $this->setAttributes([
            'server_card_info_name' =>  $operationServiceCardInfo->server_card_info_name,
            'server_card_info_value'=> $operationServiceCardInfo->server_card_info_value,
            'server_card_info_scope'=> $operationServiceCardInfo->server_card_info_scope,
            'service_card_info_rebate_value'=> $operationServiceCardInfo->service_card_info_rebate_value,
        ]);

        //7.���ɷ��񿨺�
        $service_card_with_customer_code=self::getServiceCardWithCustomerCode();

        //������Ч��������

        $service_card_with_customer_valid_at=strtotime()+$operationServiceCardInfo->service_card_info_valid_days;

        //8.��ʼ�������ֶ�
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
            'service_card_with_customer_balance'=>$operationServiceCardInfo->server_card_info_value,//���񿨴���ʱ����������ֵ
        ]);
        //9.����ͻ����񿨹�ϵ��¼
        if($this->doSave()){
            $attributes= ['service_card_with_customer_id' => $this->primaryKey,
                'service_card_with_customer_code' =>$this->service_card_sell_record_code,
                'server_card_info_value' =>$this->server_card_info_value,
                 ];
            return $attributes;//���ؿ���Ϣ

        }else{
            return null;//����NULL��˵������ʧ��
        }
    }

    /**
     * @intruction ���ɷ��񿨺�
     * @return string
     */
    private function getServiceCardWithCustomerCode(){
        $code='88888888';
        return $code;
    }

    /**
     * @intruction ��ѯ�û������з�����Ϣ
     * @param $customer_id
     * @return static[]
     */
    public static function getServiceCardWithCustomerByCustomerId($customer_id){
        return self::findAll(['customer_id'=>$customer_id]);
    }
    /**
     * ��ɾ��
     */
    public function softDelete()
    {
        $this->isdel = 1;
        return $this->save();
    }

}
