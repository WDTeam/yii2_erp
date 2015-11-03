<?php
namespace boss\models\order;

use Yii;
use yii\web\NotFoundHttpException;
use boss\models\order\OrderComplaint;
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
		$orderComplaintModel = new OrderComplaint();
		if(!empty($ocarr) && is_array($ocarr)){
			$orderComplaint = array(
					'OrderComplaint'=>array(
							'id' => $ocarr['id'],
							'complaint_section' => $ocarr['complaint_section'],
							'complaint_assortment' => $ocarr['complaint_assortment'],
							'complaint_level' => $ocarr['complaint_level'],
							'updated_at' => time()
					)
			);
			if($orderComplaintModel->load($orderComplaint) && $orderComplaintModel->save()){
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
							'handle_plan'=>$ocharr[handle_plan],
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
}