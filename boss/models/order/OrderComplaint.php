<?php

namespace boss\models\order;

use Yii;
use common\models\order\OrderExtCustomer;
use common\models\order\OrderExtWorker;
use yii\caching\ArrayCache;
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
 * @property integer $updated_at
 * @property integer $updated_at
 * @property integer $is_softdel
 */
class OrderComplaint extends \core\models\order\OrderComplaint
{
	/**
	 * 后台添加投诉业务逻辑方法
	 * @param array $params 要插入的参数数组
	 * @return boolean
	 */
    public function backInsertComplaint($params){
    	$arr = array(); $flag = false;
    	$arr['OrderComplaint']['order_id'] = $params['order_id'];
    	$arr['OrderComplaint']['complaint_detail'] = $params['complaint_detail'];
    	$arr['OrderComplaint']['complaint_phone'] = $params['cumstomer_phone'];
    	$arr['OrderComplaint']['created_at'] = time();
    	$arr['OrderComplaint']['updated_at'] = time();
    	$arr['OrderComplaint']['complaint_time'] = time();
    	$arr['OrderComplaint']['complaint_channel'] = '2';
    	foreach ($params['data'] as $key=>$val){
    		$arr['OrderComplaint']['complaint_type'] = $val['type'];
    		$arr['OrderComplaint']['complaint_section'] = $val['department'];
    		$arr['OrderComplaint']['complaint_level'] = $val['level'];
    		if($this->load($arr) && $this->save()){
    			$flag = true;
    		}
    	}
    	return $flag;
    }
}
