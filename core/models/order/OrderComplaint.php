<?php
namespace core\models\order;

use Yii;
use yii\data\ActiveDataProvider;

use core\models\order\Order;
use dbbase\models\order\OrderExtWorker;
use yii\base\Model;

class OrderComplaint extends \dbbase\models\order\OrderComplaint
{
	
    /**
     * 投诉类型
     * @return multitype:string
     */
    public static function ComplaintType(){
    	return array(
    			'1'=>'订单投诉',
    			'2'=>'非订单投诉'
    	);
    }
    /**
     *订单状态
     * @return multitype:string
     */
    public static function ComplaintStatus(){
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
    public static function ComplaintLevel(){
    	return array(
    			'1' => 'S',
    			'2' => 'A',
    			'3' => 'B',
    			'4' => 'C',
    	);
    }
    public static function Department(){
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
    public static function ComplaintTypes(){
    	return array(
    			'1'=>array(
					'1'=>'爽约',
			        '2'=>'迟到（已补时）',
					'3'=>'迟到（未补时）',
			        '4'=>'早退',
					'5'=>'私自乱收费',
			        '6'=>'工作时间看手机',	
					'7'=>'未穿工服/工具箱不全',
			        '8'=>'工具遗失损坏',
					'9'=>'仪容仪表',
			        '10'=>'培训',
    				'56'=>'不服从管理',
					'11'=>'拒接电话',
			        '12'=>'拒单',
					'13'=>'与客户抱怨',
    				'17'=>'物品损坏',
    				'18'=>'工时未达标',
    				'19'=>'服务态度差/不尊重客户',
			        '14'=>'磨洋工',
					'15'=>'浪费物品',
			        '16'=>'打扫不干净/且不愿返工',
			        '20'=>'私自/转单加价',
					'21'=>'辱骂或肢体冲突',
			        '22'=>'虚假报单',
					'23'=>'恶意骚扰',
    				'57'=>'收取客户财物',
			        '24'=>'偷盗',
					'25'=>'不使用e家洁名号',
			        '26'=>'私自带陌生人上门',
    				'58'=>'多平台接单',
					'27'=>'不满足客户合理要求',
    			),
    			'2'=>array(
					'28'=>'不使用e家洁名号',
			        '29'=>'态度、不配合',
					'30'=>'提供虚假信息',
			        '31'=>'私自更换阿姨',
					'32'=>'私自修改订单',
			        '33'=>'不及时响应',
					'34'=>'拒单',
			        '35'=>'克扣阿姨工资',
					'36'=>'阿姨被投诉',
    				'59'=>'阿姨培训',
			        '37'=>'工具派发不全',
    				'60'=>'工具使用不当',
					'38'=>'私自乱收费',
    			),
    			'3'=>array(
    				'39'=>'服务态度不满',
    				'40'=>'未履行服务承诺',
    				'41'=>'业务知识欠缺',
    				'61'=>'订单信息下发不全或有误',
    				'62'=>'虚假投诉归类',
    			),
    			'4'=>array(
    				'42'=>'胡乱承诺',
    			),
    			'5' => array(
    				'43'=>'公司制度',
    				'44'=>'服务流程',
    				'45'=>'无服务阿姨',
    				
    			),
    			'6' => array(
    				'46'=>'无法开具发票',
    				'47'=>'发票错误或延时',
    				'48'=>'金额损失',
    			),
    			'7' => array(
    				'49'=>'订单丢失',
    				'50'=>'擅自修改订单',
    				'51'=>'订单取消',
    				'52'=>'优惠码不可用',
    				'53'=>'合作平台扣费错误',
    				'54'=>'线上无法支付',
    			),
    			'8' => array(
    				'55'=>'活动方案',
    			)
    	);
    }
    
    public static function complaint_channel(){
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
    		if($this->load($narr) && $this->save()){
    			return true;
    		}else{
    			return false;
    		}
    	}else{
    		return false;
    	} 
    }
    
    /**
     * 根据阿姨ID获取对应的评论列表
     * @param type $worker_id 阿姨ID
     * @param type $current_page 当前页
     * @param type $per_page_num 每页显示数量
     * @return type
     */ 
    public static function getWorkerComplain($worker_id=0,$current_page=1,$per_page_num=10){
        $current_page = intval($current_page)>0?intval($current_page):1;
        $offset = ($current_page - 1) * $per_page_num;
        $query = new \yii\db\Query();
        $query = $query->select([
            'ocomplain.complaint_content',
            'ocomplain.complaint_time',
        ])->from('{{%order}} as order ')
            ->leftJoin('{{%order_complaint}} as ocomplain','order.id = ocomplain.order_id')
            ->andFilterWhere([
                'order_booked_worker_id' => $worker_id
            ])->limit($offset, $per_page_num);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider->query->all();
    }

    public function getOrder(){
    	return $this->hasOne(Order::className(), ['id'=>'order_id']);
    }
    
    public function getOrderExtWorker(){
    	return $this->hasOne(OrderExtWorker::className(), ['order_id'=>'order_id']);
    }
    /**
     * 根据客户手机号统计客户的投诉数量
     * @param 用户手机号 $phone
     * @return boolean
     */
    public function getComplainNumsByPhone($phone){
    	$flag = false;
    	if(!empty($phone)){
    		$model = self::find()->andWhere(["complaint_phone"=>$phone])->count("id");
    		if($model > 0){
    			return $model;
    		}else {
    			return $flag;
    		}	
    	}
    	return $flag;
    }
}
