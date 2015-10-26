<?php

namespace common\models\order;

use Yii;

/**
 * This is the model class for table "{{%order_worker_relation}}".
 *
 * @property string $id
 * @property string $created_at
 * @property string $updated_at
 * @property string $order_id
 * @property integer $worker_id
 * @property integer $admin_id
 * @property string $order_worker_relation_memo
 * @property string $order_worker_relation_status
 * @property integer $isdel
 */
class OrderWorkerRelation extends \common\models\order\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_worker_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'order_id', 'worker_id', 'admin_id', 'isdel'], 'integer'],
            [['order_worker_relation_memo', 'order_worker_relation_status'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'order_id' => '订单id',
            'worker_id' => '阿姨id',
            'admin_id' => '派单员id',
            'order_worker_relation_memo' => '订单阿姨备注',
            'order_worker_relation_status' => '订单阿姨状态',
            'isdel' => '是否已删除',
        ];
    }
}
