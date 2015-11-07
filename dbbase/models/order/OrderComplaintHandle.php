<?php

namespace dbbase\models\order;

use Yii;

/**
 * This is the model class for table "ejj_order_complaint_handle".
 *
 * @property string $id
 * @property string $order_complaint_id
 * @property string $handle_operate
 * @property string $handle_plan
 * @property string $created_at
 * @property string $updated_at
 * @property integer $handle_section
 * @property integer $is_softdel
 */
class OrderComplaintHandle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ejj_order_complaint_handle';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_complaint_id'], 'required'],
            [['order_complaint_id', 'created_at', 'updated_at', 'handle_section', 'is_softdel'], 'integer'],
            [['handle_operate'], 'string', 'max' => 20],
            [['handle_plan'], 'string', 'max' => 255],
        	['handle_plan','required','message'=>'{attribute}不能为空'],
        	['handle_section','required','message'=>'{attribute}必须选一项'],
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
            'handle_operate' => 'Handle Operate',
            'handle_plan' => '处理方案',
        	'handle_section' => '处理部门',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_softdel' => 'Is Softdel',
        ];
    }
}
