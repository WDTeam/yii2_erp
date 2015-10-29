<?php

namespace core\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\sq_ejiajie_v2\UserInfo;

/**
 * UserInfo represents the model behind the search form about `common\models\UserInfo`.
 */
class UserInfoSearch extends UserInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gender', 'free_pay_hours', 'is_block', 'user_type', 'level', 'discount', 'shop_id', 'pop_community_id', 'join_active_id', 'parent_user_id', 'telphone', 'device_type', 'is_order_response', 'is_order_recall', 'user_roll'], 'integer'],
            [['name', 'block_date', 'create_time', 'user_src', 'city_name', 'expect_service', 'wanted_type', 'street', 'extend_info', 'channel', 'education', 'birthday', 'gift_record', 'email', 'smscode', 'device_token', 'mac_add', 'update_time', 'weixin_id'], 'safe'],
            [['charge_money', 'reward_money', 'already_consum'], 'number'],
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
        $query = UserInfo::find();

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
            'gender' => $this->gender,
            'free_pay_hours' => $this->free_pay_hours,
            'is_block' => $this->is_block,
            'block_date' => $this->block_date,
            'create_time' => $this->create_time,
            'charge_money' => $this->charge_money,
            'reward_money' => $this->reward_money,
            'user_type' => $this->user_type,
            'level' => $this->level,
            'discount' => $this->discount,
            'shop_id' => $this->shop_id,
            'pop_community_id' => $this->pop_community_id,
            'join_active_id' => $this->join_active_id,
            'already_consum' => $this->already_consum,
            'birthday' => $this->birthday,
            'parent_user_id' => $this->parent_user_id,
            'telphone' => $this->telphone,
            'device_type' => $this->device_type,
            'update_time' => $this->update_time,
            'is_order_response' => $this->is_order_response,
            'is_order_recall' => $this->is_order_recall,
            'user_roll' => $this->user_roll,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'user_src', $this->user_src])
            ->andFilterWhere(['like', 'city_name', $this->city_name])
            ->andFilterWhere(['like', 'expect_service', $this->expect_service])
            ->andFilterWhere(['like', 'wanted_type', $this->wanted_type])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'extend_info', $this->extend_info])
            ->andFilterWhere(['like', 'channel', $this->channel])
            ->andFilterWhere(['like', 'education', $this->education])
            ->andFilterWhere(['like', 'gift_record', $this->gift_record])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'smscode', $this->smscode])
            ->andFilterWhere(['like', 'device_token', $this->device_token])
            ->andFilterWhere(['like', 'mac_add', $this->mac_add])
            ->andFilterWhere(['like', 'weixin_id', $this->weixin_id]);

        return $dataProvider;
    }
}
