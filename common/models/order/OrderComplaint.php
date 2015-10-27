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
 */



class OrderComplaint extends \common\models\order\ActiveRecord
{
	public $order_customer_phone;
	public $order_worker_phone;
	public $order_worker_name;
	public $order_worker_type_name;
	public $order_worker_shop_name;
	public $worker_id;
	public $order_id;

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
    	return $this->hasOne(OrderExtCustomer::className(), ['order_id'=>'order_id']);
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
    public function Department(){
    	return array(
    			'1' => '阿姨',
    			'2' => '家政公司',
    			'3' => '客服',
    			'4' => '线下推广',
    			'5' => '公司',
    			'6' => '财务',
    			'7' =>'系统',
    			'8' =>'活动',
    	);
    }
    public function ComplaintTypes(){
    	return array(
    			'1'=>array(
    					'1'=>'爽约','2'=>'迟到（已补时）',
    					'3'=>'迟到（未补时）','4'=>'早退',
    					'5'=>'私自乱收费','6'=>'工作时间看手机',
    					'7'=>'未穿工服/工具箱不全','8'=>'工具遗失损坏',
    					'9'=>'仪容仪表','10'=>'培训',
    					'11'=>'拒接电话','12'=>'拒单',
    					'13'=>'与客户抱怨','14'=>'磨洋工',
    					'15'=>'浪费物品','16'=>'打扫不干净/且不愿返工',
    					'17'=>'被投诉返工','18'=>'服务态度差/不尊重客户',
    					'19'=>'未安装阿姨端APP','20'=>'私自/转单加价',
    					'21'=>'辱骂或肢体冲突','22'=>'虚假报单',
    					'23'=>'恶意骚扰','24'=>'偷盗',
    					'25'=>'不使用e家洁名号',	'26'=>'私自带陌生人上门',
    					'27'=>'不满足客户合理要求',
    			),
    			'2'=>array(
    					'1'=>'不使用e家洁名号',''=>'态度、不配合问题',
    					'2'=>'提供虚假信息',''=>'私自更换阿姨',
    					'3'=>'克扣工资',''=>'培训',
    					'4'=>'拒单',''=>'擅自修改订单',
    					'5'=>'不及时联系客户',''=>'工具派发不全',
    					'6'=>'私自乱收费'
    					
    			),
    			'3'=>array(
    				'1'=>'服务态度不满',
    				'2'=>'未履行服务承诺',
    				'3'=>'业务知识欠缺',	
    			),
    			'4'=>array(
    				'1'=>'胡乱承诺'
    			),
    			'5' => array(
    				'1'=>'公司制度',
    				'2'=>'服务流程',
    				'3'=>'无服务阿姨'
    				
    			),
    			'6' => array(
    				'1'=>'无法开具发票',
    				'2'=>'发票错误或延时',
    				'3'=>'金额损失'
    			),
    			'7' => array(
    				'1'=>'订单丢失',
    				'2'=>'擅自修改订单',
    				'3'=>'订单取消',
    				'4'=>'优惠码不可用',
    				'5'=>'合作平台扣费错误',
    				'6'=>'线上无法支付'
    			),
    			'8' => array(
    				'1'=>'活动方案'
    			)
    			
    	);
    }
    public function complaint_channel(){
    	return array(
    			'1' => 'app',
    			'2' => '后台',
    			'3' => '第三方'
    	);
    }
    /**
     * app投诉添加业务逻辑
     * @param array $arr
     */
    public function appModel($arr){
    	if(!empty($arr) && is_array($arr)){
    		$arr['complaint_channel'] = '1';
    		$narr = array('OrderComplaint'=>$arr);
    		$this->load($data);$this->save();
    	}else{
    		return false;
    	} 
    }
}
