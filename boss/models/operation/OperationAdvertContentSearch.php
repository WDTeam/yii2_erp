<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationAdvertContent;

/**
 * OperationAdvertContentSearch represents the model behind the search form about `boss\models\operation\OperationAdvertContent`.
 */
class OperationAdvertContentSearch extends OperationAdvertContent
{
    public function rules()
    {
        return [
            [['id', 'position_id', 'platform_id', 'platform_version_id', 'operation_advert_start_time', 'operation_advert_end_time', 'operation_advert_online_time', 'operation_advert_offline_time', 'is_softdel', 'created_at', 'updated_at', 'operation_advert_content_orders'], 'integer'],
            [['operation_advert_content_name', 'position_name', 'platform_name', 'platform_version_name', 'operation_advert_picture_text', 'operation_advert_url'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationAdvertContent::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'position_id' => $this->position_id,
            'platform_id' => $this->platform_id,
            'platform_version_id' => $this->platform_version_id,
            'operation_advert_start_time' => $this->operation_advert_start_time,
            'operation_advert_end_time' => $this->operation_advert_end_time,
            'operation_advert_online_time' => $this->operation_advert_online_time,
            'operation_advert_offline_time' => $this->operation_advert_offline_time,
            'is_softdel' => $this->is_softdel,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'operation_advert_content_orders' => $this->operation_advert_content_orders,
        ]);

        $query->andFilterWhere(['like', 'operation_advert_content_name', $this->operation_advert_content_name])
            ->andFilterWhere(['like', 'position_name', $this->position_name])
            ->andFilterWhere(['like', 'platform_name', $this->platform_name])
            ->andFilterWhere(['like', 'platform_version_name', $this->platform_version_name])
            ->andFilterWhere(['like', 'operation_advert_picture_text', $this->operation_advert_picture_text])
            ->andFilterWhere(['like', 'operation_advert_url', $this->operation_advert_url]);

        return $dataProvider;
    }
}
