<?php
namespace boss\models\order;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\order\OrderComplaint;

class OrderComplaintSearch extends OrderComplaint{
	
	public function rules(){
		return [
				[['id','order_id','ejj_worker.id'], 'integer'],
				[['complaint_content', 'order_customer_phone', 'worker_phone','worker_name'], 'safe'],
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
		$query->joinWith("order_ext_customer");
		$query->joinWith("order_ext_worker");
		//$query->joinWith("worker");
		$query->orderBy([OrderComplaint::tableName().".id"=>SORT_DESC]);
		
		$dataProvider = new ActiveDataProvider([
				'query' => $query,
		]);
		
		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}
		$query->andFilterWhere([
				'order_id' => $this->order_id,
				'ejj_worker.id' => $this->id
		]);
		
		$query->andFilterWhere(['like','worker_name',$this->worker_name])->
		andFilterWhere(['like','order_customer_phone',$this->order_customer_phone])->
		andFilterWhere(['likt','worker_phone',$this->worker_phone]);
		if($this->complaint_time != '')
		{
			$query->andFilterWhere(['between', 'order_complaint.complaint_time', $this->complaint_time . ' 00:00:00', $this->reserve_time . ' 23:59:59']);
		}
		return 	$dataProvider;
	}
}