<?php

namespace boss\models\order;

use Yii;
use common\models\order\OrderExtCustomer;
use common\models\order\OrderExtWorker;
use common\models\worker\Worker;
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
            [['order_id', 'complaint_phone', 'complaint_time'], 'required'],
            [['order_id', 'complaint_type', 'complaint_status', 'complaint_channel', 'complaint_section', 'complaint_time'], 'integer'],
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
    public function getOrder_ext_customer(){
    	return $this->hasMany(OrderExtCustomer::className(), ['order_id'=>'order_id']);
    }
    public function getOrder_ext_worker(){
    	return $this->hasOne(OrderExtWorker::className(), ['order_id'=>'order_id']);
    }
    /**
     * 投诉类型
     * @return multitype:string
     */
    public function ComplaintType(){
    	return array(
    			'1'=>'订单投诉',
    			'2'=>'非订单投诉'
    	);
    }
    /**
     *订单状态
     * @return multitype:string
     */
    public function ComplaintStatus(){
    	return array(
    			'1' => '待确认',
    			'2' => '待处理',
    			'3' => '处理中',
    			'4' => '待回复',
    			'5' => '已完成',
    			'6' => '无效投诉',
    	);
    }
    /**
     * 投诉级别
     * @return multitype:string
     */
    public function ComplaintLevel(){
    	return array(
    			'1' => 'S',
    			'2' => 'A',
    			'3' => 'B',
    			'4' => 'C',
    	);
    }
   /*  public function getWorker(){
    	return $this->hasMany(Worker::className(), ['worker_id'=>'id']);
    } */
}
