<?php

namespace boss\models\finance;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\finance\FinanceOrderChannel;

/**
 * FinanceOrderChannelSearch represents the model behind the search form about `dbbase\models\FinanceOrderChannel`.
 */
class FinanceOrderChannelSearch extends FinanceOrderChannel
{
	
	
    public function rules()
    {
        return [
            [['id', 'finance_order_channel_sort', 'finance_order_channel_is_lock', 'create_time', 'is_del'], 'integer'],
            [['finance_order_channel_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    
    public static  function get_sourcedate($key,$stype)
    {
    	$date=['0'=>'未知','1'=>'APP','2'=>'H5','3'=>'PC','4'=>'POP','5'=>'API'];
    	if($stype==1){
    		return $date[$key];
    	}else{
    		return $date;
    	}
    	
    }
    
    
    public function searchfind($where,$flsde){
    
    	$dsfsd=$this->find()->where($where)->asArray()->one();
    	 
    	return $dsfsd[$flsde];
    }
    
    
    public function search($params)
    {
        $query = FinanceOrderChannel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'finance_order_channel_sort' => $this->finance_order_channel_sort,
            'finance_order_channel_is_lock' => $this->finance_order_channel_is_lock,
            'create_time' => $this->create_time,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'finance_order_channel_name', $this->finance_order_channel_name]);

        return $dataProvider;
    }
}
