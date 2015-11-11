<?php
namespace dbbase\models\order;

use Yii;

use dbbase\models\ActiveRecord;
/**
 * This is the model class for table "ejj_order_complaint".
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $order_code_number
 * @property string $complaint_code_number
 * @property integer $complaint_type
 * @property integer $complaint_status
 * @property integer $complaint_channel
 * @property integer $complaint_section
 * @property integer $complaint_assortment
 * @property string $complaint_level
 * @property string $complaint_phone
 * @property string $complaint_content
 * @property integer $complaint_time
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_softdel
 */



class OrderComplaint extends ActiveRecord
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
            [['order_id'], 'required'],
            [['order_id', 'complaint_type', 'complaint_status', 'complaint_channel', 'complaint_section', 'complaint_assortment', 'complaint_time', 'created_at', 'updated_at', 'is_softdel'], 'integer'],
            [['complaint_content'], 'string'],
            [['complaint_level'], 'string', 'max' => 2],
            [['complaint_phone'], 'string', 'max' => 16],
        	[['complaint_code_number','order_code_number'], 'string', 'max' => 64]
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
            'complaint_type' => 'Complaint Type',
            'complaint_status' => 'Complaint Status',
            'complaint_channel' => 'Complaint Channel',
            'complaint_section' => 'Complaint Section',
            'complaint_assortment' => 'Complaint Assortment',
            'complaint_level' => 'Complaint Level',
            'complaint_phone' => 'Complaint Phone',
            'complaint_content' => 'Complaint Content',
            'complaint_time' => 'Complaint Time',
        ];
    }
}
