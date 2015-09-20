<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Help;

/**
 * HelpSearch represents the model behind the search form about `common\models\Help`.
 */
class HelpSearch extends Help
{
    public function rules()
    {
        return [
            [['id', 'help_status', 'help_sort', 'created_at', 'update_at'], 'integer'],
            [['help_question', 'help_solution'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Help::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'help_status' => $this->help_status,
            'help_sort' => $this->help_sort,
            'created_at' => $this->created_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'help_question', $this->help_question])
            ->andFilterWhere(['like', 'help_solution', $this->help_solution]);

        return $dataProvider;
    }
}
