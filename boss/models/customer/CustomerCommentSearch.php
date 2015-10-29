<?php

namespace boss\models\customer;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\customer\CustomerComment;

/**
 * CustomerCommentSearch represents the model behind the search form about `\dbbase\models\CustomerComment`.
 */
class CustomerCommentSearch extends CustomerComment
{
    public function rules()
    {
        return [
            [['id', 'order_id', 'customer_id', 'updated_at', 'is_del'], 'integer'],
            [['customer_comment_phone', 'customer_comment_content'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public $created_at_end;
    public function search()
    {
        $query = CustomerComment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if($this->created_at){ $statime=strtotime($this->created_at);}else { $statime= null;}
        if($this->created_at_end){$endtime=strtotime($this->created_at_end);}else{$endtime= null; }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
	        'worker_tel' => $this->worker_tel,
	        'operation_shop_district_id' => $this->operation_shop_district_id,
	        'province_id' => $this->province_id,
        	'city_id' => $this->city_id,
        	'county_id' => $this->county_id,
	        'customer_comment_level' => $this->customer_comment_level,	
            'customer_id' => $this->customer_id,
            'operation_shop_district_id' => $this->operation_shop_district_id,
            'customer_comment_anonymous' => $this->customer_comment_anonymous,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'customer_comment_phone', $this->customer_comment_phone])
        ->andFilterWhere(['between', 'created_at',$statime,$endtime])
        ->andFilterWhere(['like', 'customer_comment_content', $this->customer_comment_content]);

        return $dataProvider;
    }
}
