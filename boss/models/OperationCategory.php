<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OperationCategory as OperationCategoryModel;

/**
 * OperationCategory represents the model behind the search form about `common\models\OperationCategory`.
 */
class OperationCategory extends OperationCategoryModel
{
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['operation_category_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationCategoryModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'operation_category_name', $this->operation_category_name]);

        return $dataProvider;
    }
}
