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
	
	public function rules(){
		return [
				[['id','order_id','complaint_type','complaint_status','complaint_section','complaint_channel'], 'integer'],
				[[ 'complaint_phone', 'order_worker_phone','order_worker_name','complaint_level'], 'safe'],
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
		$query->joinWith("orderExtCustomer");
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
				'id' => $this->id,
				'ejj_order_complaint.order_id'=> $this->order_id,
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
		andFilterWhere(['like','ejj_order_ext_worker.order_worker_phone',$this->order_worker_phone]);
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
			$param = $session->get("param");$path = $session->get("path");
			$url = http_build_query($param);
			return $url;
		}else{
			return false;
		}
		
	}
}