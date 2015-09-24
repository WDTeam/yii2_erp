<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WorkerBlock;

/**
 * WorkerBlockSearch represents the model behind the search form about `common\models\WorkerBlock`.
 */
class WorkerBlockSearch extends WorkerBlock
{
    public function rules()
    {
        return [
            [['id', 'worker_id', 'worker_block_start_time', 'worker_block_finish_time', 'created_ad', 'updated_ad', 'admin_id'], 'integer'],
            [['worker_block_reason'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = WorkerBlock::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'worker_id' => $this->worker_id,
            'worker_block_start_time' => $this->worker_block_start,
            'worker_block_finish_time' => $this->worker_block_finish,
            'created_ad' => $this->created_ad,
            'updated_ad' => $this->updated_ad,
            'admin_id' => $this->admin_id,
        ]);

        $query->andFilterWhere(['like', 'worker_block_reason', $this->worker_block_reason]);

        return $dataProvider;
    }
}
