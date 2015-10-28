<?php
namespace boss\models\order;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\order\OrderComplaint;
use yii\base\ExitException;

class OrderComplaintSearch extends OrderComplaint{
	
	public function rules(){
		return [
				[['id','order_id','worker_id'], 'integer'],
				[['complaint_content', 'order_customer_phone', 'order_worker_phone','order_worker_name','order_worker_type_name','order_worker_shop_name'], 'safe'],
		];
	}
	
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}
	public function search($params)
	{
		unset($params['s']);
		$query = OrderComplaint::find();
		$query->joinWith("order_ext_customer");
		$query->joinWith("order_ext_worker");
		//$query->joinWith("worker");
		$query->orderBy([OrderComplaint::tableName().".id"=>SORT_DESC]);
		
		$dataProvider = new ActiveDataProvider([
				'query' => $query,
		]);
		$model = $dataProvider->getModels();
 		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}
		$query->andFilterWhere([
				'id' => $this->id,
				'worker_id' => $this->worker_id,
				'order_id'=> $this->order_id,
				'complaint_type' => $this->complaint_type,
				'complaint_status' => $this->complaint_status,
				'complaint_channel' => $this->complaint_channel,
				'complaint_phone' => $this->complaint_phone,
				'complaint_section' => $this->complaint_section,
				'complaint_level' => $this->complaint_level,
				'complaint_content' => $this->complaint_content,
				'complaint_time' => $this->complaint_time,
				'created_at' => $this->created_at,
				'updated_at' => $this->updated_at,
				'order_customer_phone' => $this->order_customer_phone,
				'order_worker_phone' => $this->order_worker_phone,
				'order_worker_name' => $this->order_worker_name,
				'order_worker_type_name' => $this->order_worker_type_name,
				'order_worker_shop_name' => $this->order_worker_shop_name
				
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