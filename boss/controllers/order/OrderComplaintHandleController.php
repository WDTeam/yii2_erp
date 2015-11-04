<?php
namespace boss\controllers\order;

use Yii;

use boss\components\BaseAuthController;
use boss\models\order\OrderComplaintHandle;
use boss\models\order\OrderComplaintSearch;

class OrderComplaintHandleController extends BaseAuthController{
	public function actionIndex(){
		$model = new OrderComplaint();
		print_r($result);exit("2324342");
	}
	public function actionCreate(){
		$model = new OrderComplaintHandle();
		$complaintSearchmodel = new OrderComplaintSearch();
		$id = Yii::$app->request->getQueryParam('id');
		if(!empty($id)){
			$orderComplaintModel = $complaintSearchmodel->getOrder_Complaint_DetailById($id);
			$ocharr = $model->getOrder_Complaint_Handle($orderComplaintModel->id);
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
					'ochModel'=>$ocharr		
					]
					);
		}else{
			$parmas = Yii::$app->request->post();
			//修改订单投诉记录
			$uocresult = $model->updateOrderComplaint($parmas['OrderComplaint']);
			//添加投诉处理操作记录
			$aochresult = $model->addOrderComplaintHandle($parmas['OrderComplaintHandle']);
			if($uocresult && $aochresult){
				$this->redirect('order/order-complaint/');
			}
		}
	}
}