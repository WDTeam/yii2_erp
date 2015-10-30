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
class OperationServiceCardConsumeRecord extends \dbbase\models\operation\OperationServiceCardConsumeRecord
{
    /**
     * save service card consume record
     * @author zhangrenzhao
     * @param $attributes
     * ��   order_id, ����ID
     *      order_code,������
     *      customer_id,�û�ID
     *      customer_trans_record_transaction_id,������ˮ
     *      service_card_with_customer_id,�ͻ�����ID
     *      service_card_with_customer_code,�ͻ����񿨺�
     *      service_card_consume_record_consume_type,��������
     *      service_card_consume_record_business_type,ҵ������
     *      service_card_consume_record_use_money��ʹ�ý�� ��
     * @return bool
     */
    public function createServiceCardConsumeRecord($attributes)
    {
        //1.��ȡattributes����д��this��������
        $this->setAttributes($attributes);

        //2.����service_card_with_customer_id��ѯ�ͷ�������Ϣ
        $operationServiceCardWithCustomer = OperationServiceCardWithCustomer::getServiceCardWithCustomerById($this->service_card_with_customer_id);

        //3.д��ʹ��ǰ���
        $this->setAttributes([
            'service_card_consume_record_front_money' => $operationServiceCardWithCustomer->service_card_with_customer_balance,
        ]);

        //4.����ʹ�ú���
        $this->service_card_consume_record_behind_money = $this->service_card_with_customer_balance - $this->service_card_consume_record_use_money;

        //5.д��ʹ�ú���
        $this->setAttributes([
            'service_card_consume_record_behind_money' => $this->service_card_consume_record_behind_money,
        ]);

        //6.���������ֶ�
        $this->setAttributes([
            'isdel' => 0,//�߼�ɾ��
        ]);

        //7.�������Ѽ�¼����ɹ������¿ͻ��������
        if($this->doSave()){
            return OperationServiceCardWithCustomer::updateServiceCardWithCustomerBalanceById($this->service_card_with_customer_id,$this->service_card_consume_record_behind_money);
        }else{
            return false;
        }

    }
    /**
     * @introduction �߼�ɾ��
     * @return bool
     */
    public function softDelete()
    {
        $this->isdel = 1;
        return $this->save();
    }
}
