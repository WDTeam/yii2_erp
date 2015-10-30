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
     * @instruction �ͻ�ȷ��������񿨣�����������¼
     * @author zhangrenzhao
     * @param $attributes
     * ��   customer_id,�û�ID
     *      server_card_info_id,����ϢID
     *      service_card_sell_record_status����������״̬
     *      service_card_sell_record_channel_id,��������ID
     *      service_card_sell_record_channel_name,������������
     *      service_card_sell_record_money,����������� ��
     * @return string
     */
    public function createServiceCardSellRecord($attributes)
    {
        //1.��ȡattributes��д��this��������
        $this->setAttributes($attributes);

        //2.����server_card_info_id��ѯ������Ϣ
        $operationServiceCardInfo = OperationServiceCardInfo::getServiceCardInfoById($this->server_card_info_id);

        //3.д�������Ϣ
        $this->setAttributes([
            'service_card_info_name' => $operationServiceCardInfo->service_card_info_name,//������
        ]);

        //4.����customer_id����ȡ�û�����
        $customer = Customer::getCustomerById($this->customer_id);

        //5.д���û���Ϣ
        $this->setAttributes([
            'order_customer_phone' => $customer->customer_phone,
        ]);

        //6.���ɹ������۶�����
        $service_card_sell_record_code=self::getService_card_sell_record_code();

        //7.��ʼ�������ֶ�
        $this->setAttributes([
            'service_card_sell_record_code'=>$service_card_sell_record_code,
            'is_del' => 0,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);

        //8.���湺�����ۼ�¼
        if($this->doSave()){
            return $this->primaryKey;//���ع�������ID
        }else{
            return null;//����NULL��˵������ʧ��
        }
    }

    /**
     * @introduction �ͻ�֧���ɹ�����д֧����Ϣ
     * @param $attributes
     * �� id,���񿨶�����¼
     *    customer_id,�û�ID
     *    server_card_info_id,����ϢID
     *    service_card_sell_record_status����������״̬
     *    customer_trans_record_pay_mode��֧����ʽ
     *    pay_channel_id��֧������ID
     *    customer_trans_record_pay_channel��֧����������
     *    customer_trans_record_transaction_id��֧��������ˮ
     *    customer_trans_record_pay_money��֧�����
     *    customer_trans_record_pay_account,֧���˻�
     *    customer_trans_record _paid_at��֧��ʱ��
     * @return tool
     */
    public function backServiceCardSellRecord($attributes)
    {
        //1.��ȡattributes��д��this��������
        $this->setAttributes($attributes);
        //2.���ø���ʱ��
        $this->setAttributes([
            'updated_at'=>time(),
        ]);
        //3.�����д��Ϣ
        if($this->dosave()){
            //��д�ɹ������ɿͻ������ϵ��¼
           return OperationServiceCardWithCustomer::createServiceCardWithCustomer(
               $this->id,
               $this->customer_id,
               $this->customer_trans_record_pay_money,
               $this->customer_trans_record_paid_at);

        }else{
            return null;//NULL��ʾ��������ʧ��
        }
    }

    /**
     * @instruction ���ɹ�����������
     * @return mixed
     */
    private function getService_card_sell_record_code(){
        $code='99999999';
        return code;
    }

    /**
     * @instruction ����ID��ȡ�������ۼ�¼
     * @param $id
     */
    public function getServiceCardSellRecordById($id){
        return self::findOne(['id'=>$id]);
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
