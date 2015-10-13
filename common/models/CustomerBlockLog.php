<?php

namespace common\models;

use Yii;
use common\models\Customer;
/**
 * This is the model class for table "{{%customer_block_log}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $customer_block_log_status
 * @property string $customer_block_log_reason
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class CustomerBlockLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_block_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'customer_block_log_status', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['customer_block_log_reason'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('boss', '编号'),
            'customer_id' => Yii::t('boss', '客户'),
            'customer_block_log_status' => Yii::t('boss', '状态，1为黑名单0为正常'),
            'customer_block_log_reason' => Yii::t('boss', '原因'),
            'created_at' => Yii::t('boss', '创建时间'),
            'updated_at' => Yii::t('boss', '更新时间'),
            'is_del' => Yii::t('boss', '是否逻辑删除'),
        ];
    }

    /**
     * 客户加入黑名单
     */
    public static function addToBlock($customer_id, $customer_block_log_reason){
        $customer = Customer::findOne($customer_id);
        if ($customer == NULL) {
            return false;
        }
        if ($customer->is_del) {
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customer->is_del = 1;
            $customer->customer_del_reason = $customer_block_log_reason;
            $customer->validate();
            if ($customer->hasErrors()) {
                return false;
            }
            $customer->save();
            $customerBlockLog = new CustomerBlockLog;
            $customerBlockLog->customer_id = $customer_id;
            $customerBlockLog->customer_block_log_status = 1;
            $customerBlockLog->customer_block_log_reason = $customer_block_log_reason;
            $customerBlockLog->created_at = time();
            $customerBlockLog->updated_at = 0;
            $customerBlockLog->is_del = 0;
            $customerBlockLog->validate();
            $customerBlockLog->save();
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollback();
            return false;
        }
        
    }

    /**
     * 从黑名单删除
     */
    public static function removeFromBlock($customer_id){
        $customer = Customer::findOne($customer_id);
        if ($customer == NULL) {
            return false;
        }
        if (!$customer->is_del) {
            return false;
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try{
            $customer->is_del = 0;
            $customer->customer_del_reason = '';
            $customer->validate();
            if ($customer->hasErrors()) {
                return false;
            }
            $customer->save();
            $customerBlockLog = new CustomerBlockLog;
            $customerBlockLog->customer_id = $customer_id;
            $customerBlockLog->customer_block_log_status = 0;
            $customerBlockLog->customer_block_log_reason = '';
            $customerBlockLog->created_at = time();
            $customerBlockLog->updated_at = 0;
            $customerBlockLog->is_del = 0;
            $customerBlockLog->validate();
            $customerBlockLog->save();
            $transaction->commit();
            return true;
        }catch(\Exception $e){
            $transaction->rollback();
            return false;
        }
    }

    /**
     * 列出黑名单历史记录
     */
    public static function listBlockLog($customer_id){
        $customer = Customer::findOne($customer_id);
        if ($customer == NULL) {
            return false;
        }
        $customer_block_logs = self::findAll(['customer_id'=>$customer_id]);
        if (empty($customer_block_logs)) {
            return false;
        }
        return $customer_block_logs;
    }

    /**
     * 获取当前状态
     */
    public static function getCurrentBlockStatus($customer_id){
        $customer = Customer::findOne($customer_id);
        if ($customer == NULL) {
            return false;
        }
        $block_status_name = '';
        switch ($customer->is_del) {
            case 0:
                $block_status_name = '正常';
                break;
            case 1:
                $block_status_name = '黑名单';
                break;
            default:
                # code...
                break;
        }
        return array(
            'is_del'=>$customer->is_del,
            'block_status_name'=>$block_status_name,
            );
    }
}
