<?php

namespace common\models\order;

use Yii;
use common\models\order\OrderExtCustomer;
use common\models\order\OrderExtWorker;
/**
 * This is the model class for table "ejj_order_complaint".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $complaint_type
 * @property integer $complaint_status
 * @property integer $complaint_channel
 * @property integer $complaint_section
 * @property string $complaint_level
 * @property string $complaint_phone
 * @property string $complaint_content
 * @property integer $complaint_time
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_at
 * @property integer $is_softdel
 */



class OrderComplaint extends \common\models\order\ActiveRecord
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
            [['order_id', 'complaint_phone', 'complaint_time'], 'required'],
            [['order_id', 'complaint_type', 'complaint_status', 'complaint_channel', 'complaint_section', 'complaint_time', 'created_at', 'updated_at', 'is_softdel'], 'integer'],
            [['complaint_content'], 'string'],
            [['complaint_level'], 'string', 'max' => 2],
            [['complaint_phone'], 'string', 'max' => 16]
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
            'complaint_level' => 'Complaint Level',
            'complaint_phone' => 'Complaint Phone',
            'complaint_content' => 'Complaint Content',
            'complaint_time' => 'Complaint Time',
        ];
    }
    
    public function getOrderExtCustomer(){
    	return $this->hasOne(OrderExtCustomer::className(), ['order_id'=>'order_id']);
    }
    
    public function getOrderExtWorker(){
    	return $this->hasOne(OrderExtWorker::className(), ['order_id'=>'order_id']);
    }
}
