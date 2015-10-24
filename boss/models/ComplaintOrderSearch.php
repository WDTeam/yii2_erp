<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\ComplaintOrder;

/**
 * ComplaintOrderSearch represents the model behind the search form about `boss\models\ComplaintOrder`.
 */
class ComplaintOrderSearch extends ComplaintOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'worker_id', 'complaint_type', 'complaint_section', 'complaint_time'], 'integer'],
            [['complaint_level', 'complaint_content'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ComplaintOrder::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'worker_id' => $this->worker_id,
            'complaint_type' => $this->complaint_type,
            'complaint_section' => $this->complaint_section,
            'complaint_time' => $this->complaint_time,
        ]);

        $query->andFilterWhere(['like', 'complaint_level', $this->complaint_level])
            ->andFilterWhere(['like', 'complaint_content', $this->complaint_content]);

        return $dataProvider;
    }
}
