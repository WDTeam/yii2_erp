<?php

namespace core\models\worker;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\models\worker\WorkerTask;

/**
 * WorkerTaskSearch represents the model behind the search form about `core\models\worker\WorkerTask`.
 */
class WorkerTaskSearch extends WorkerTask
{
    public function rules()
    {
        return [
            [['id', 'worker_task_start', 'worker_task_end', 'worker_type', 'worker_rule_id', 'worker_task_city_id', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['worker_task_name', 'worker_task_description', 'worker_task_description_url', 'conditions'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = WorkerTask::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'worker_task_start' => $this->worker_task_start,
            'worker_task_end' => $this->worker_task_end,
            'worker_type' => $this->worker_type,
            'worker_rule_id' => $this->worker_rule_id,
            'worker_task_city_id' => $this->worker_task_city_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'worker_task_name', $this->worker_task_name])
            ->andFilterWhere(['like', 'worker_task_description', $this->worker_task_description])
            ->andFilterWhere(['like', 'worker_task_description_url', $this->worker_task_description_url])
            ->andFilterWhere(['like', 'conditions', $this->conditions]);

        return $dataProvider;
    }
}
