<?php

namespace boss\models\order;

use Yii;
use yii\caching\ArrayCache;
use boss\models\order\OrderComplaintHandle;

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
 * @property string $complaint_level
 * @property string $complaint_phone
 * @property string $complaint_content
 * @property integer $complaint_time
 * @property integer $updated_at
 * @property integer $updated_at
 * @property integer $is_softdel
 */
class OrderComplaint extends \core\models\order\OrderComplaint
{
	/**
	 * 返回重组后的数组对象
	 * @param array $params 要插入的参数数组
	 * @return boolean
	 */
    public function order_Complaint($params){
    	$arr = array(); $flag = false;$narr = array();
    	$code = $params['order_code'];$new_order_code = "";
    	if(!empty($code)){
    		$num = strlen($code);
    		$order_code = substr($code,2,$num-2);
    		$new_order_code = "04".$order_code;
    	}
    	$arr['OrderComplaint']['order_code_number'] = strval($code);
    	$arr['OrderComplaint']['complaint_code_number'] = strval($new_order_code);
    	$arr['OrderComplaint']['order_id'] = $params['order_id'];
    	$arr['OrderComplaint']['complaint_content'] = strval($params['complaint_detail']);
    	$arr['OrderComplaint']['complaint_phone'] = strval($params['cumstomer_phone']);
    	$arr['OrderComplaint']['complaint_status'] = '1';
    	$arr['OrderComplaint']['complaint_type'] = '1';
    	$arr['OrderComplaint']['created_at'] = time();
    	$arr['OrderComplaint']['updated_at'] = time();
    	$arr['OrderComplaint']['is_softdel'] = '0';
    	$arr['OrderComplaint']['complaint_time'] = time();
    	$arr['OrderComplaint']['complaint_channel'] = '2';
    	foreach ($params['data'] as $key=>$val){
    		$arr['OrderComplaint']['complaint_assortment'] = $val['type'];
    		$arr['OrderComplaint']['complaint_section'] = $val['department'];
    		$arr['OrderComplaint']['complaint_level'] = strval($val['level']);
		 $narr[] = $arr;	
    	}
    	return $narr;
    }
    /**
     * 添加订单投诉业务逻辑方法
     * @param unknown $arr
     * @return boolean
     */
    public function backInsertOrderComplaint($arr){
    	var_dump($this->load($arr) && $this->save());
    	print_r($this->errors);exit();
  			$flag = false;
    		if($this->load($arr) && $this->save()){
    			$flag = true;
    		};
    	return $flag;
    }
    /**
     * 根据键值返回渠道
     * @param unknown $num
     */
    public function channel($num){
    	$arr = self::complaint_channel();
    	return @$arr[$num];
    }
    /**
     * 根据部门Id,类型Id返回投诉类型
     * @param unknown $dnum
     * @param unknown $num
     */
    public function ctype($dnum,$num){
    	$arr = self::ComplaintTypes();
    	return @$arr[$dnum][$num];
    }
    /**
     * 根据部门Id返回部门
     * @param unknown $nums
     */
    public function section($nums){
    	$arr = self::Department();
    	return @$arr[$nums];
    }
   	/**
   	 * 根据投诉的Id返回级别
   	 * @param unknown $num
   	 */
    public function level($num){
    	$arr = self::ComplaintLevel();
    	return @$arr[$num];
    }
    /**
     * 根据投诉Id返回处理投诉的具体内容
     * @param integer $orderComplaintId 
     * @return string $result 返回处理的内容
     */
    public function getOrderComplaintHandleDetail($orderComplaintId){
    	$ochdModel = new OrderComplaintHandle();
    	$ocharr = $ochdModel->getOrder_Complaint_Handle($orderComplaintId);
    	$result = $ochdModel->complaintDetail($ocharr);
    	return $result;
    }
}
