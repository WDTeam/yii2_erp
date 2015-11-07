<?php
namespace boss\models\order;

use Yii;
use yii\web\NotFoundHttpException;
use boss\models\order\OrderComplaint;
use boss\models\order\OrderComplaintHandleLog;
use core\models\order\OrderComplaintHandle as OrderComplaintHandleCoreModel;

class OrderComplaintHandle extends OrderComplaintHandleCoreModel{
	
	/**
	 * 根据投诉ID信息获取投诉
	 * @param unknown $Id
	 * @throws NotFoundHttpException
	 * @return Ambigous <\yii\db\static, NULL>|boolean
	 */
	public function getOrder_Complaint_Handle($id){
		$flag = false;
		if(!empty($id)){
			$model=self::find()->where(['order_complaint_id'=>$id])->all();
			$nums = sizeof($model);
			if($nums > 0){
				return $model;
			}else{
				return $flag;
			}	
		}else{
			return $flag;
		}
	}
	/**
	 * 获取投诉部门
	 * @return multitype:string
	 */
	public function get_Complaint_Section(){
		
		return OrderComplaint::Department();
		
	}
	/**
	 * 获取投诉类型
	 * @return multitype:multitype:string
	 */
	public function get_Complaint_Assortment(){
		
		return OrderComplaint::ComplaintTypes();
		
	}
	/**
	 * 获取投诉部门
	 * @return multitype:string
	 */
	public function get_Handle_Section(){
		
		return OrderComplaint::Department();
		
	}
	/**
	 * 获取投诉的状态
	 * @return multitype:string
	 */
	public function get_Complaint_Status(){
		
		return OrderComplaint::ComplaintStatus();
		
	}
	/**
	 * 获取投诉级别
	 * @return multitype:string
	 */
	public function get_Complaint_Level(){
		
		return OrderComplaint::ComplaintLevel();
		
	}
	/**
	 * 修改订单投诉
	 * @param array $ocarr 要修改的订单投诉字段值
	 * @return multitype:multitype:unknown number
	 */
	public function updateOrderComplaint($ocarr){
		
		$orderComplaintModel = OrderComplaint::findOne($ocarr['id']);
		$orderComplaintModel->isNewRecord = false;
		if(!empty($ocarr) && is_array($ocarr)){
			$orderComplaintarr = array(
					'OrderComplaint'=>array(
							'id' => $ocarr['id'],
							'complaint_section' => $ocarr['complaint_section'],
							'complaint_assortment' => $ocarr['complaint_assortment'],
							'complaint_level' => $ocarr['complaint_level'],
							'complaint_status' => $ocarr['complaint_status'],
							'updated_at' => time()
					)
			);
			if($orderComplaintModel->load($orderComplaintarr) && $orderComplaintModel->save()){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	/**
	 * 添加操作记录
	 * @param array $ocharr 要插入的投诉操作字段值
	 * @return boolean
	 */
	public function addOrderComplaintHandle($ocharr){
		if(!empty($ocharr) && is_array($ocharr)){
			$orderComplaintHandle=array(
					'OrderComplaintHandle'=>array(
							'order_complaint_id'=>$ocharr['order_complaint_id'],
							'handle_section'=>$ocharr['handle_section'],
							'handle_plan'=>$ocharr['handle_plan'],
							'handle_operate'=>Yii::$app->user->identity->username,
							'created_at'=>time(),
							'updated_at'=>time(),
							'is_softdel'=>'0',
					)
			);
			if($this->load($orderComplaintHandle) && $this->save()){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	/**
	 * 获取投诉的所有处理方案
	 * @param array $model 数组对象
	 * @return unknown
	 */
	public function complaintDetail($model){
		$content="";
		if(!empty($model) && is_array($model)){
			foreach ($model as $key=>$ochModel){
				$content .= $ochModel->handle_plan; 
			}
			return $content;
		}else{
			return false;
		}
	}
	/**
	 * 添加处理投诉日志
	 * @param array $params 参数字段
	 * @param object $orderComplaintModel 处理投诉对象
	 * @param interger $orderComplaintId  订单投诉id
	 */
	public function addOrderComplaintHandleLog($params,$orderComplaintModel,$orderComplaintId){
		$orderComplaintHandleModel = $this->getOneOrderComplaintHandle($orderComplaintId);
		$ochId = !empty($orderComplaintHandleModel->id)? $orderComplaintHandleModel->id:0;
		$complaint_section = $params['OrderComplaint']['complaint_section'];
		$complaint_assortment = $params['OrderComplaint']['complaint_assortment'];
		$complaint_level = $params['OrderComplaint']['complaint_level'];
		$complaint_status = $params['OrderComplaint']['complaint_status'];
		$handle_section = $params['OrderComplaintHandle']['handle_section'];
		$csarr = $this->get_Complaint_Section();             //投诉部门
		$caarr = $this->get_Complaint_Assortment();			 //投诉类型
		$clarr = $this->get_Complaint_Level();				 //投诉级别
		$cssarr = $this->get_Complaint_Status();			 //投诉状态
		$csharr = $this->get_Handle_Section();				 //处理部门
		$modelarr = array(
				'OrderComplaintHandleLog'=>array(
						'order_complaint_id'=>$orderComplaintId,
						'order_complaint_handle_id'=>$ochId,
						'handle_operate'=>Yii::$app->user->identity->username,
						'created_at'=>time(),
						'updated_at'=>time(),
						'is_softdel'=>0
				)
		);
		//投诉部门变更日志
		if(!empty($complaint_section) && $complaint_section != $orderComplaintModel->complaint_section){
			$ochlModel = new OrderComplaintHandleLog();
			$modelarr['OrderComplaintHandleLog']['status_before'] = @$csarr[$orderComplaintModel->complaint_section];
			$modelarr['OrderComplaintHandleLog']['status_after'] =  @$csarr[$complaint_section];
			$modelarr['OrderComplaintHandleLog']['handle_option'] = '投诉部门';
			$ochlModel->add($modelarr);
		}
		//投诉类型变更日志
		if(!empty($complaint_assortment) && $complaint_assortment != $orderComplaintModel->complaint_assortment){
			$ochlModel = new OrderComplaintHandleLog();
			$modelarr['OrderComplaintHandleLog']['status_before'] = @$caarr[$orderComplaintModel->complaint_section][$orderComplaintModel->complaint_assortment];
			$modelarr['OrderComplaintHandleLog']['status_after'] =  @$caarr[$complaint_section][$complaint_assortment];
			$modelarr['OrderComplaintHandleLog']['handle_option'] = '投诉类型';
			$ochlModel->add($modelarr);
		}
		//投诉级别变更日志
		if(!empty($complaint_level) && $complaint_level != $orderComplaintModel->complaint_level){
			$ochlModel = new OrderComplaintHandleLog();
			$modelarr['OrderComplaintHandleLog']['status_before'] = @$clarr[$orderComplaintModel->complaint_level];
			$modelarr['OrderComplaintHandleLog']['status_after'] = @$clarr[$complaint_level];
			$modelarr['OrderComplaintHandleLog']['handle_option'] = '投诉级别';
			$ochlModel->add($modelarr);
		}
		//投诉状态变更日志
		if(!empty($complaint_status) && $complaint_status != $orderComplaintModel->complaint_status){
			echo "4";
			$ochlModel = new OrderComplaintHandleLog();
			$modelarr['OrderComplaintHandleLog']['status_before'] = @$cssarr[$orderComplaintModel->complaint_status];
			$modelarr['OrderComplaintHandleLog']['status_after'] = @$cssarr[$complaint_status];
			$modelarr['OrderComplaintHandleLog']['handle_option'] = '投诉状态';
			$ochlModel->add($modelarr);
		}
		//投诉处理部门日志变更记录
		if(!empty($handle_section)){
			if(!empty($orderComplaintHandleModel) && $handle_section != $orderComplaintHandleModel->handle_section){
				echo "5";
				$ochlModel = new OrderComplaintHandleLog();
				$modelarr['OrderComplaintHandleLog']['status_before'] = @$csharr[$orderComplaintHandleModel->handle_section];
				$modelarr['OrderComplaintHandleLog']['status_after'] = @$csharr[$handle_section];
				$modelarr['OrderComplaintHandleLog']['handle_option'] = '处理部门';
				$ochlModel->add($modelarr);
			}
		}
	}
	/**
	 * 根据订单投诉Id查询处理投诉最近的日志记录
	 * @param int $orderComplaintId 投诉记录Id
	 * @return unknown|boolean
	 */
	public function getOneOrderComplaintHandle($orderComplaintId){
		$model = self::find()->where(['order_complaint_id'=>$orderComplaintId])->orderBy("id DESC")->one();
		if($model != null){
			return $model;
		}else{
			return false;
		}
	}
	/**
	 * 根据订单投诉Id查找日志记录
	 * @param unknown $orderComplaintId
	 * @return unknown|boolean
	 */
	public function getOrderComplaintHandleLogById($orderComplaintId){
		$ochModel = new OrderComplaintHandleLog();
		if(!empty($orderComplaintId)){
			$result = $ochModel->findAllByOrderComplaintId($orderComplaintId);
			return $result;
		}else{
			return false;
		}
		
	}
}