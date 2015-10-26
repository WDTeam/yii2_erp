<?php

namespace boss\models\operation;

use Yii;

/**
 * This is the model class for table "ejj_order_complaint".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $worker_id
 * @property integer $complaint_type
 * @property integer $complaint_section
 * @property string $complaint_level
 * @property integer $complaint_phone
 * @property string $complaint_content
 * @property integer $complaint_time
 */
class OrderComplaint extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ejj_order_complaint';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'worker_id', 'complaint_phone', 'complaint_time'], 'required'],
            [['order_id', 'worker_id', 'complaint_type', 'complaint_section', 'complaint_phone', 'complaint_time'], 'integer'],
            [['complaint_content'], 'string'],
            [['complaint_level'], 'string', 'max' => 2]
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
            'worker_id' => 'Worker ID',
            'complaint_type' => 'Complaint Type',
            'complaint_section' => 'Complaint Section',
            'complaint_level' => 'Complaint Level',
            'complaint_phone' => 'Complaint Phone',
            'complaint_content' => 'Complaint Content',
            'complaint_time' => 'Complaint Time',
        ];
    }
}
