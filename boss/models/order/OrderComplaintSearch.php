<?php
namespace boss\models\order;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\base\ExitException;
use yii\web\Session;

use boss\models\order\OrderComplaint;

class OrderComplaintSearch extends OrderComplaint{
	public $order_worker_name;
	public $order_worker_phone;
	public $order_code;
	
	public function rules(){
		return [
				[['complaint_type','complaint_status','complaint_section','complaint_channel'], 'integer'],
				[['complaint_phone', 'order_worker_phone','order_worker_name','complaint_level','order_code','complaint_code_number'], 'safe'],
				[['order_worker_phone','complaint_code_number','complaint_phone','order_worker_name','order_code'],'trim'],
				['order_worker_phone','match','pattern'=>'/1[3458]{1}\d{9}$/'],
				['complaint_phone','match','pattern'=>'/1[3458]{1}\d{9}$/'],
				['complaint_code_number','match','pattern'=>'/\d{1,20}$/'],
				['order_code','match','pattern'=>'/\d{1,20}$/'],
				['order_worker_name','string','min'=>2,'max'=>20],
		];
	}
	public function attributeLabels(){
		return [
			'id'=>'投诉编号',
			'order_code'=>'订单编号',
			'complaint_phone'=>'客户手机',
			'order_worker_phone'=>'阿姨手机',
			'order_worker_name'=>'阿姨姓名'
		];
	}
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}
	public function search($params)
	{
		
		$query = OrderComplaint::find();
		$query->joinWith("order");
		$query->joinWith("orderExtWorker");
		$query->orderBy([OrderComplaint::tableName().".id"=>SORT_DESC]);
		$dataProvider = new ActiveDataProvider([
				'query' => $query,
				'pagination' => [
                		'pagesize' => '20',
				]
		]);
 		if (!($this->load($params) && $this->validate())) {
 			return $dataProvider;
		}
		$query->andFilterWhere([
				OrderComplaint::tableName().'.id' => $this->id,
				'complaint_type' => $this->complaint_type,
				'complaint_status' => $this->complaint_status,
				'complaint_channel' => $this->complaint_channel,
				'complaint_section' => $this->complaint_section,
				'complaint_level' => $this->complaint_level,
				'complaint_time' => $this->complaint_time,
				'created_at' => $this->created_at,
				'updated_at' => $this->updated_at,
				
		]);
		$query->andFilterWhere(['like','ejj_order_ext_worker.order_worker_name',$this->order_worker_name])->
		andFilterWhere(['like',OrderComplaint::tableName().'.complaint_phone',$this->complaint_phone])->
		andFilterWhere(['like','ejj_order_ext_worker.order_worker_phone',$this->order_worker_phone])->
		andFilterWhere(['like',OrderComplaint::tableName().'.complaint_code_number',$this->complaint_code_number])->
		andFilterWhere(['like','ejj_order.order_code',$this->order_code]);
		if(!empty($params['starttime']) && !empty($params['endtime'])){
			$query->andFilterWhere(['between', OrderComplaint::tableName().'.created_at', strtotime($params['starttime']), strtotime($params['endtime'])]);
		}
		
		return 	$dataProvider;
	}
	/**
	 * 对url的分类进行存储检测
	 * @param unknown $params
	 * @return string|boolean
	 */
	public function urlParameterProcessing($params){
		$session = Yii::$app->session;
		unset($params['r']);
		if(!empty($params) && is_array($params)){
			$session->set('param', $params);
			$param = $session->get("param");
			$url = http_build_query($param);
			return $url;
		}else{
			return false;
		}
		
	}
	/**
	 * 根据投诉ID信息获取投诉
	 * @param unknown $Id
	 * @throws NotFoundHttpException
	 * @return Ambigous <\yii\db\static, NULL>|boolean
	 */
	public function getOrder_Complaint_DetailById($id){
	
		$query = OrderComplaint::find()->
		joinWith("order")->
		joinWith("orderExtWorker")->
		where(['ejj_order_complaint.id'=>$id])->one();
		$dataProvider = new ActiveDataProvider([
				'query'=>$query
		]);
		if($query != null){
			return $query;
		}else{
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}