<?php
namespace boss\models\order;

use Yii;
use core\models\order\OrderComplaintHandleLog as OrderComplaintHandleLogCoreModel;

class OrderComplaintHandleLog extends OrderComplaintHandleLogCoreModel{
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
}