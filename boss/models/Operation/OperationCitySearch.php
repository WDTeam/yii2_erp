<?php

namespace boss\models\Operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\Operation\CoreOperationCity;

/**
 * OperationCitySearch represents the model behind the search form about `common\models\OperationCity`.
 */
class OperationCitySearch extends OperationCity
{
    public function rules()
    {
        return [
            [['id', 'city_is_online', 'created_at', 'updated_at'], 'integer'],
            [['city_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        if(!empty($params)){
            $query = OperationCity::find()->where(['like',$params['fields'], $params['keyword']]);
        }else{
            $query = OperationCity::find();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $dataProvider;
//        if (!($this->load($params) && $this->validate())) {
//            return $dataProvider;
//        }

//        $query->andFilterWhere([
//            $params['fields'] => $params['keyword'],
//        ]);

//        $query->andFilterWhere(['like', 'city_name', $this->city_name]);

//        return $dataProvider;
    }
}
