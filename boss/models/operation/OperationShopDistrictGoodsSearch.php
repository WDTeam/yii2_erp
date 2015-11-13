<?php

namespace boss\models\operation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use boss\models\operation\OperationShopDistrictGoods;

/**
 * OperationShopDistrictGoodsSearch represents the model behind the search form about `boss\models\operation\OperationShopDistrictGoods`.
 */
class OperationShopDistrictGoodsSearch extends OperationShopDistrictGoods
{

    public function rules()
    {
        return [
            [['id', 'operation_goods_id', 'operation_shop_district_id', 'operation_city_id', 'operation_category_id', 'operation_shop_district_goods_service_interval_time', 'operation_shop_district_goods_service_estimate_time', 'operation_spec_info', 'operation_shop_district_goods_lowest_consume_num', 'operation_shop_district_goods_status', 'is_softdel', 'created_at', 'updated_at'], 'integer'],
            [['operation_shop_district_goods_name', 'operation_shop_district_goods_no', 'operation_shop_district_name', 'operation_city_name', 'operation_category_ids', 'operation_category_name', 'operation_shop_district_goods_introduction', 'operation_shop_district_goods_english_name', 'operation_shop_district_goods_start_time', 'operation_shop_district_goods_end_time', 'operation_shop_district_goods_service_time_slot', 'operation_spec_strategy_unit', 'operation_shop_district_goods_price_description', 'operation_tags', 'operation_goods_img', 'operation_shop_district_goods_app_ico', 'operation_shop_district_goods_pc_ico'], 'safe'],
            [['operation_shop_district_goods_price', 'operation_shop_district_goods_balance_price', 'operation_shop_district_goods_additional_cost', 'operation_shop_district_goods_lowest_consume', 'operation_shop_district_goods_market_price', 'district_nums'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = OperationShopDistrictGoods::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query->select([
                'operation_city_id',
                'operation_goods_id',
                'operation_category_id',
                'operation_city_name',
                'operation_shop_district_name',
                'operation_category_name',
                'operation_shop_district_goods_name',
                'count(*) as district_nums'
            ])->where(['operation_shop_district_goods_status' => 1])
            ->groupBy([
                'operation_city_id',
                'operation_goods_id'
            ]),
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'operation_city_id' => $this->operation_city_id,
            'operation_category_id' => $this->operation_category_id,
            'operation_shop_district_goods_service_interval_time' => $this->operation_shop_district_goods_service_interval_time,
            'operation_shop_district_goods_service_estimate_time' => $this->operation_shop_district_goods_service_estimate_time,
            'operation_shop_district_goods_price' => $this->operation_shop_district_goods_price,
            'operation_shop_district_goods_balance_price' => $this->operation_shop_district_goods_balance_price,
            'operation_shop_district_goods_additional_cost' => $this->operation_shop_district_goods_additional_cost,
            'operation_shop_district_goods_lowest_consume_num' => $this->operation_shop_district_goods_lowest_consume_num,
            'operation_shop_district_goods_lowest_consume' => $this->operation_shop_district_goods_lowest_consume,
            'operation_shop_district_goods_market_price' => $this->operation_shop_district_goods_market_price,
            'operation_shop_district_goods_status' => $this->operation_shop_district_goods_status,
            'is_softdel' => $this->is_softdel,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'operation_shop_district_goods_name', $this->operation_shop_district_goods_name])
            ->andFilterWhere(['like', 'operation_shop_district_name', $this->operation_shop_district_name])
            ->andFilterWhere(['like', 'operation_city_name', $this->operation_city_name])
            ->andFilterWhere(['like', 'operation_category_name', $this->operation_category_name])
            ->andFilterWhere(['like', 'operation_spec_strategy_unit', $this->operation_spec_strategy_unit])
            ->andFilterWhere(['like', 'operation_shop_district_goods_price_description', $this->operation_shop_district_goods_price_description]);

        //$query->groupBy(['operation_city_id', 'operation_goods_id']);

        return $dataProvider;
    }
}
