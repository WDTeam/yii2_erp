<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Courseware;

/**
 * CoursewareSearch represents the model behind the search form about `common\models\Courseware`.
 */
class CoursewareSearch extends Courseware
{
    public function rules()
    {
        return [
            [['id', 'pv', 'order_number', 'classify_id'], 'integer'],
            [['image', 'url', 'name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Courseware::find()->orderby('order_number ASC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'pv' => $this->pv,
            'order_number' => $this->order_number,
            'classify_id' => $this->classify_id,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'name', $this->name]);
        return $dataProvider;
    }
}
