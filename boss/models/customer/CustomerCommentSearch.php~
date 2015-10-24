<?php

namespace boss\models\customer;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\customer\CustomerComment;

/**
 * CustomerCommentSearch represents the model behind the search form about `\common\models\CustomerComment`.
 */
class CustomerCommentSearch extends CustomerComment
{
    public function rules()
    {
        return [
            [['id', 'order_id', 'customer_id', 'customer_comment_star_rate', 'customer_comment_anonymous', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_comment_phone', 'customer_comment_content'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CustomerComment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'customer_id' => $this->customer_id,
            'customer_comment_star_rate' => $this->customer_comment_star_rate,
            'customer_comment_anonymous' => $this->customer_comment_anonymous,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'customer_comment_phone', $this->customer_comment_phone])
            ->andFilterWhere(['like', 'customer_comment_content', $this->customer_comment_content]);

        return $dataProvider;
    }
}
