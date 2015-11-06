<?php
namespace boss\controllers\order;

use Yii;

use boss\components\BaseAuthController;
use boss\models\order\OrderComplaintHandle;
use boss\models\order\OrderComplaintSearch;

class OrderComplaintHandleController extends BaseAuthController{
	public function actionIndex(){
		$model = new OrderComplaint();
		exit();
	}
	public function actionCreate(){
		$model = new OrderComplaintHandle();
		$complaintSearchmodel = new OrderComplaintSearch();
		$id = Yii::$app->request->getQueryParam('id');
		if(!empty($id)){
			$orderComplaintModel = $complaintSearchmodel->getOrder_Complaint_DetailById($id);
			$ocharr = $model->getOrder_Complaint_Handle($orderComplaintModel->id);				//操作记录
			$ochlogModel = $model->getOrderComplaintHandleLogById($orderComplaintModel->id);	//操作日志记录
			$content = $model->complaintDetail($ocharr);
			$complaintSection = $model->get_Complaint_Section();
			$complaintAssortment = $model->get_Complaint_Assortment();
			$handleSection = $model->get_Handle_Section();
			$complaintStatus = $model->get_Complaint_Status();
			$complaintLevel = $model->get_Complaint_Level();
			return $this->render("_form",[
					'model' => $model,
					'orderComplaintModel' => $orderComplaintModel,
					'complaintSection' => $complaintSection,
					'complaintAssortment' => $complaintAssortment,
					'handleSection' => $handleSection,
					'complaintStatus' => $complaintStatus,
					'complaintLevel' => $complaintLevel,
					'content'=>$content,
					'ochModel'=>$ocharr,							//操作记录
					'ochlogModel'=>$ochlogModel                     //操作日志记录
					]
					);
		}else{
			$parmas = Yii::$app->request->post();
			if(!empty($parmas) && is_array($parmas)){
				$octModel = $complaintSearchmodel->getOrder_Complaint_DetailById($parmas['OrderComplaint']['id']);
				$model->addOrderComplaintHandleLog($parmas,$octModel,$parmas['OrderComplaint']['id']);
				//修改订单投诉记录
				$uocresult = $model->updateOrderComplaint($parmas['OrderComplaint']);
				//添加投诉处理操作记录
				$aochresult = $model->addOrderComplaintHandle($parmas['OrderComplaintHandle']);
				if($uocresult && $aochresult){
					$this->redirect('/order/order-complaint/');
				}	
			}else{
				die("异常错误！");
			}
		}
	}
}