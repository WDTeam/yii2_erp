<?php

namespace boss\models\operation\coupon;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dbbase\models\operation\coupon\CouponRule as CouponRuleModel;

/**
 * CouponRule represents the model behind the search form about `dbbase\models\operation\coupon\CouponRule`.
 */
class CouponRule extends CouponRuleModel
{
    public function rules()
    {
        return [
            [['id', 'couponrule_classify', 'couponrule_category', 'couponrule_type', 'couponrule_service_type_id', 'couponrule_commodity_id', 'couponrule_city_limit', 'couponrule_city_id', 'couponrule_customer_type', 'couponrule_get_start_time', 'couponrule_get_end_time', 'couponrule_use_start_time', 'couponrule_use_end_time', 'couponrule_use_end_days', 'couponrule_promote_type', 'couponrule_code_num', 'couponrule_code_max_customer_num', 'is_disabled', 'created_at', 'updated_at', 'is_del', 'system_user_id'], 'integer'],
            [['couponrule_name', 'couponrule_channelname', 'couponrule_category_name', 'couponrule_type_name', 'couponrule_service_type_name', 'couponrule_commodity_name', 'couponrule_city_name', 'couponrule_customer_type_name', 'couponrule_code', 'couponrule_Prefix', 'couponrule_promote_type_name', 'system_user_name'], 'safe'],
            [['couponrule_order_min_price', 'couponrule_price', 'couponrule_price_sum'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = CouponRuleModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'couponrule_classify' => $this->couponrule_classify,
            'couponrule_category' => $this->couponrule_category,
            'couponrule_type' => $this->couponrule_type,
            'couponrule_service_type_id' => $this->couponrule_service_type_id,
            'couponrule_commodity_id' => $this->couponrule_commodity_id,
            'couponrule_city_limit' => $this->couponrule_city_limit,
            'couponrule_city_id' => $this->couponrule_city_id,
            'couponrule_customer_type' => $this->couponrule_customer_type,
            'couponrule_get_start_time' => $this->couponrule_get_start_time,
            'couponrule_get_end_time' => $this->couponrule_get_end_time,
            'couponrule_use_start_time' => $this->couponrule_use_start_time,
            'couponrule_use_end_time' => $this->couponrule_use_end_time,
            'couponrule_use_end_days' => $this->couponrule_use_end_days,
            'couponrule_promote_type' => $this->couponrule_promote_type,
            'couponrule_order_min_price' => $this->couponrule_order_min_price,
            'couponrule_price' => $this->couponrule_price,
            'couponrule_price_sum' => $this->couponrule_price_sum,
            'couponrule_code_num' => $this->couponrule_code_num,
            'couponrule_code_max_customer_num' => $this->couponrule_code_max_customer_num,
            'is_disabled' => $this->is_disabled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_del' => $this->is_del,
            'system_user_id' => $this->system_user_id,
        ]);

        $query->andFilterWhere(['like', 'couponrule_name', $this->couponrule_name])
            ->andFilterWhere(['like', 'couponrule_channelname', $this->couponrule_channelname])
            ->andFilterWhere(['like', 'couponrule_category_name', $this->couponrule_category_name])
            ->andFilterWhere(['like', 'couponrule_type_name', $this->couponrule_type_name])
            ->andFilterWhere(['like', 'couponrule_service_type_name', $this->couponrule_service_type_name])
            ->andFilterWhere(['like', 'couponrule_commodity_name', $this->couponrule_commodity_name])
            ->andFilterWhere(['like', 'couponrule_city_name', $this->couponrule_city_name])
            ->andFilterWhere(['like', 'couponrule_customer_type_name', $this->couponrule_customer_type_name])
            ->andFilterWhere(['like', 'couponrule_code', $this->couponrule_code])
            ->andFilterWhere(['like', 'couponrule_Prefix', $this->couponrule_Prefix])
            ->andFilterWhere(['like', 'couponrule_promote_type_name', $this->couponrule_promote_type_name])
            ->andFilterWhere(['like', 'system_user_name', $this->system_user_name]);

        return $dataProvider;
    }
}
