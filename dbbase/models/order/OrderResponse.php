<?php

namespace dbbase\models\order;

use Yii;

/**
 * This is the model class for table "ejj_order_response".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $order_operation_user
 * @property integer $order_response_times
 * @property integer $order_reply_result
 * @property integer $order_response_or_not
 * @property integer $order_response_result
 * @property integer $is_softdel
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $remark
 */
class OrderResponse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_response}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_response_times', 'order_reply_result', 'order_response_or_not', 'order_response_result'], 'required'],
            [['order_id', 'order_response_times', 'order_reply_result', 'order_response_or_not', 'order_response_result', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['order_operation_user'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'order_operation_user' => 'Order Operation User',
            'order_response_times' => 'Order Response Times',
            'order_reply_result' => 'Order Reply Result',
            'order_response_or_not' => 'Order Response Or Not',
            'order_response_result' => 'Order Response Result',
            'is_softdel' => 'Is Softdel',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'remark' => 'Remark',
        ];
    }
}
