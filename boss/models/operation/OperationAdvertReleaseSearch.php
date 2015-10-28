<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationAdvertRelease;

/**
 * OperationAdvertReleaseSearch represents the model behind the search form about `boss\models\operation\OperationAdvertRelease`.
 */
class OperationAdvertReleaseSearch extends OperationAdvertRelease
{
    public function rules()
    {
        return [
            [['id', 'advert_content_id', 'city_id', 'status', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['city_name', 'starttime', 'endtime'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationAdvertRelease::find()
            ->joinWith(['operationAdvertContent']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'advert_content_id' => $this->advert_content_id,
            'city_id' => $this->city_id,
            'starttime' => $this->starttime,
            'endtime' => $this->endtime,
            'status' => $this->status,
            'is_softdel' => $this->is_softdel,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'platform_name' => $this->platform_name,
            'platform_version_id' => $this->platform_version_id,
        ]);

        $query->andFilterWhere(['like', 'city_name', $this->city_name])
            ->andFilterWhere(['like', 'operation_advert_content_name', $this->operation_advert_content_name]);

        return $dataProvider;
    }
}
