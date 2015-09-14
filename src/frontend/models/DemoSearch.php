<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * DemoSearch represents the model behind the search form about `\common\models\User`.
 */
class DemoSearch extends User
{
    public function rules()
    {
        return [
            [['common_mobile', 'username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'idnumber', 'birthday', 'mobile', 'ecp', 'ecn', 'address', 'city', 'province', 'district', 'whatodo', 'isdel', 'test_situation'], 'safe'],
            [['id', 'age', 'from_type', 'when', 'status', 'created_at', 'updated_at', 'study_status', 'study_time', 'notice_status', 'online_exam_time', 'online_exam_score', 'online_exam_mode', 'exam_result', 'operation_time', 'operation_score', 'test_status', 'test_result', 'sign_status', 'user_status'], 'integer'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'age' => $this->age,
            'birthday' => $this->birthday,
            'from_type' => $this->from_type,
            'when' => $this->when,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'study_status' => $this->study_status,
            'study_time' => $this->study_time,
            'notice_status' => $this->notice_status,
            'online_exam_time' => $this->online_exam_time,
            'online_exam_score' => $this->online_exam_score,
            'online_exam_mode' => $this->online_exam_mode,
            'exam_result' => $this->exam_result,
            'operation_time' => $this->operation_time,
            'operation_score' => $this->operation_score,
            'test_status' => $this->test_status,
            'test_result' => $this->test_result,
            'sign_status' => $this->sign_status,
            'user_status' => $this->user_status,
        ]);

        $query->andFilterWhere(['like', 'common_mobile', $this->common_mobile])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'idnumber', $this->idnumber])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'ecp', $this->ecp])
            ->andFilterWhere(['like', 'ecn', $this->ecn])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'district', $this->district])
            ->andFilterWhere(['like', 'whatodo', $this->whatodo])
            ->andFilterWhere(['like', 'isdel', $this->isdel])
            ->andFilterWhere(['like', 'test_situation', $this->test_situation]);

        return $dataProvider;
    }
}
