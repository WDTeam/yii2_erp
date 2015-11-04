<?php

namespace dbbase\models\order;

use Yii;

/**
 * This is the model class for table "ejj_order_complaint_handle_log".
 *
 * @property string $id
 * @property string $order_complaint_id
 * @property string $order_complaint_handle_id
 * @property string $handle_operate
 * @property string $handle_option
 * @property string $status_before
 * @property string $status_after
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_softdel
 */
class OrderComplaintHandleLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ejj_order_complaint_handle_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_complaint_id', 'order_complaint_handle_id', 'created_at', 'updated_at', 'is_softdel'], 'integer'],
            [['handle_operate'], 'string', 'max' => 2],
            [['handle_option'], 'string', 'max' => 20],
            [['status_before', 'status_after'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_complaint_id' => 'Order Complaint ID',
            'order_complaint_handle_id' => 'Order Complaint Handle ID',
            'handle_operate' => 'Handle Operate',
            'handle_option' => 'Handle Option',
            'status_before' => 'Status Before',
            'status_after' => 'Status After',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_softdel' => 'Is Softdel',
        ];
    }
}
