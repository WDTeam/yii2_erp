<?php

namespace dbbase\models\finance;

use Yii;

/**
 * This is the model class for table "{{%finance_worker_cannot_settle_order_reason}}".
 *
 * @property integer $id
 * @property integer $exception_node_id
 * @property string $exception_node_code
 * @property integer $exception_node_status
 * @property string $exception_node_comment
 * @property integer $worker_id
 * @property integer $order_id
 * @property string $order_code
 * @property integer $finance_worker_settle_apply_starttime
 * @property integer $finance_worker_settle_apply_endtime
 * @property integer $is_softdel
 * @property integer $updated_at
 * @property integer $created_at
 */
class FinanceWorkerCannotSettleOrderReason extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%finance_worker_cannot_settle_order_reason}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exception_node_id', 'exception_node_code', 'exception_node_status', 'exception_node_comment', 'worker_id', 'order_id', 'order_code'], 'required'],
            [['exception_node_id', 'exception_node_status', 'worker_id', 'order_id', 'finance_worker_settle_apply_starttime', 'finance_worker_settle_apply_endtime', 'is_softdel', 'updated_at', 'created_at'], 'integer'],
            [['exception_node_comment'], 'string'],
            [['exception_node_code', 'order_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键'),
            'exception_node_id' => Yii::t('app', '异常节点id,例如赔偿id、投诉id'),
            'exception_node_code' => Yii::t('app', '异常节点编号，例如赔偿编号、投诉编号'),
            'exception_node_status' => Yii::t('app', '异常节点状态'),
            'exception_node_comment' => Yii::t('app', '异常节点备注，包括赔偿原因、投诉原因等'),
            'worker_id' => Yii::t('app', '阿姨id'),
            'order_id' => Yii::t('app', '订单id'),
            'order_code' => Yii::t('app', '订单编号'),
            'finance_worker_settle_apply_starttime' => Yii::t('app', '本次结算开始时间(统计)，例如：2015.9.1 00:00:00对应的int值'),
            'finance_worker_settle_apply_endtime' => Yii::t('app', '本次结算结束时间(统计)，例如：2015.9.30 23:59:59对应的int值'),
            'is_softdel' => Yii::t('app', '是否被删除，0为启用，1为删除'),
            'updated_at' => Yii::t('app', '结算时间'),
            'created_at' => Yii::t('app', '创建时间'),
        ];
    }
}
