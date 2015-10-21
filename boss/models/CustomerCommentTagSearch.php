<?php

namespace boss\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\CustomerCommentTag;

/**
 * CustomerCommentTagSearch represents the model behind the search form about `\common\models\CustomerCommentTag`.
 */
class CustomerCommentTagSearch extends CustomerCommentTag
{
    public function rules()
    {
        return [
            [['id', 'customer_comment_level', 'is_online', 'created_at', 'updated_at', 'is_del'], 'integer'],
            [['customer_comment_tag_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CustomerCommentTag::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'customer_comment_level' => $this->customer_comment_level,
            'is_online' => $this->is_online,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
        ]);

        $query->andFilterWhere(['like', 'customer_comment_tag_name', $this->customer_comment_tag_name]);

        return $dataProvider;
    }
}
