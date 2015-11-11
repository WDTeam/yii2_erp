<?php

namespace boss\models\operation;

use boss\models\operation\OperationAdvertPosition;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OperationAdvertPositionSearch represents the model behind the search form about `boss\models\operation\OperationAdvertPosition`.
 */
class OperationAdvertPositionSearch extends OperationAdvertPosition
{
    public function rules()
    {
        return [
            [['id', 'operation_platform_id', 'operation_platform_version_id', 'operation_advert_position_width', 'operation_advert_position_height', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['operation_advert_position_name', 'operation_platform_name', 'operation_platform_version_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationAdvertPosition::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'operation_platform_id' => $this->operation_platform_id,
            'operation_platform_version_id' => $this->operation_platform_version_id,
            'operation_advert_position_width' => $this->operation_advert_position_width,
            'operation_advert_position_height' => $this->operation_advert_position_height,
            'is_softdel' => $this->is_softdel,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'operation_advert_position_name', $this->operation_advert_position_name])
            ->andFilterWhere(['like', 'operation_platform_name', $this->operation_platform_name])
            ->andFilterWhere(['like', 'operation_platform_version_name', $this->operation_platform_version_name]);

        return $dataProvider;
    }
}
