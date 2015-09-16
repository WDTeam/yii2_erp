<?php

namespace frontend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\User;

/**
 * UserSearch represents the model behind the search form about `frontend\models\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'age', 'status', 'created_at', 'updated_at', 'study_status', 'study_time', 'notice_status', 'online_exam_time', 'online_exam_score', 'exam_result', 'operation_time', 'operation_score', 'test_status', 'test_result', 'sign_status'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'idnumber', 'birthday', 'mobile', 'ecn', 'city', 'province', 'district', 'whatodo', 'where', 'when', 'isdel', 'test_situation'], 'safe'],
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
        $query = User::find();

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
            'age' => $this->age,
            'birthday' => $this->birthday,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'study_status' => $this->study_status,
            'study_time' => $this->study_time,
            'notice_status' => $this->notice_status,
            'online_exam_time' => $this->online_exam_time,
            'online_exam_score' => $this->online_exam_score,
            'exam_result' => $this->exam_result,
            'operation_time' => $this->operation_time,
            'operation_score' => $this->operation_score,
            'test_status' => $this->test_status,
            'test_result' => $this->test_result,
            'sign_status' => $this->sign_status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'idnumber', $this->idnumber])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'ecn', $this->ecn])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'whatodo', $this->whatodo])
            ->andFilterWhere(['like', 'where', $this->where])
            ->andFilterWhere(['like', 'when', $this->when])
            ->andFilterWhere(['like', 'isdel', $this->isdel])
            ->andFilterWhere(['like', 'test_situation', $this->test_situation]);

        return $dataProvider;
    }
}
