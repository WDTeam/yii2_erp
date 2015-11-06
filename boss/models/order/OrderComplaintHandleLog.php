<?php
namespace boss\models\order;

use Yii;

class OrderComplaintHandleLog extends \core\models\order\OrderComplaintHandleLog{
	/**
	 * 添加处理投诉日志
	 * @param array $arr 要插入的数组字段
	 * @return boolean
	 */
	public function add($arr){
		if($this->load($arr) && $this->save()){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 根据订单投诉Id返回日志记录
	 * @param integer $orderComplaintId
	 * @return boolean
	 */
	public function findAllByOrderComplaintId($orderComplaintId){
		$model = $this->find()->where(['order_complaint_id'=>$orderComplaintId])->all();
		$nums = sizeof($model);
		if(($model != null) && ($nums >0)){
			return $model;
		}else{
			return false;
		}
	}
}