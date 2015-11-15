<?php

namespace boss\models\order;

use core\models\order\OrderStatusDict;
use Yii;
use yii\data\ActiveDataProvider;
use boss\models\worker\Worker;

/*
 * 仅用于订单查询主页面!!!!!!!!!!!!!!!!!!!!!!!
 *   
 */

class OrderSearchIndex extends Order
{
    public $created_from;
    public $created_to;
    public $assign_from;
    public $assign_to;
    public $booked_from;
    public $booked_to;

    public $cities;
    public $types;
    public $statuss;
    public $channels;

    public function rules()
    {
        return [
            [['order_code', 'order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'isdel', 'order_ip', 'order_service_type_id', 'channel_id', 'order_booked_count', 'order_booked_begin_time', 'order_booked_end_time', 'address_id', 'order_booked_worker_id',  'shop_id', 'district_id', 'city_id'], 'integer'],
            [['order_unit_money', 'order_money'], 'number'],
            [['order_channel_name', 'created_from', 'created_to', 'booked_from', 'booked_to'], 'string', 'max' => 64],
            [['order_customer_phone', 'order_worker_phone'], 'match', 'pattern' => '/^\d{11}$/i', 'message' => '请填写正确的电话号码或格式！(11位数字)'],
            [['order_service_type_name'], 'string', 'max' => 128],
            [['order_address', 'order_cs_memo'], 'string', 'max' => 255],
            [['order_status_dict_id', 'cities', 'types', 'statuss', 'channels'], 'safe'],
        ];
    }

    public function getBookedWorker()
    {
        return $this->hasOne(Worker::className(), ['id' => 'order_booked_worker_id'])->from(Worker::tableName() . ' AS bookedWorker');
    }

    public function search($params)
    {
        $query = OrderSearchIndex::find()->joinWith(['orderExtPop', 'orderExtCustomer', 'orderExtWorker', 'orderExtStatus', 'orderExtPay', 'bookedWorker'])
            ->orderBy(['id' => SORT_DESC]);
        if (Yii::$app->user->identity->isNotAdmin()) {
            $query->where(['orderExtWorker.shop_id' => Yii::$app->user->identity->shopIds]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//             'pagination' => [
////                 'pageSize' => 2,
//             ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'order_parent_id' => $this->order_parent_id,
            'order_is_parent' => $this->order_is_parent,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
            'isdel' => $this->isdel,
            'order_ip' => $this->order_ip,
            'order_service_type_id' => $this->order_service_type_id,
            'channel_id' => $this->channel_id,
            'order_unit_money' => $this->order_unit_money,
            'order_money' => $this->order_money,
            'order_booked_count' => $this->order_booked_count,
            //'order_booked_begin_time' => $this->order_booked_begin_time,
            //'order_booked_end_time' => $this->order_booked_end_time,
            'address_id' => $this->address_id,
            //'order_booked_worker_id' => $this->order_booked_worker_id,
            'order_pop_order_code' => $this->order_pop_order_code,
            'order_customer_phone' => $this->order_customer_phone,
            //'order_worker_phone' => $this->order_worker_phone,
            'orderExtWorker.shop_id' => $this->shop_id,
            'district_id' => $this->district_id,
            'city_id' => $this->city_id,
            'order_worker_assign_type' => empty($this->order_worker_assign_type)?'':($this->order_worker_assign_type == 1 ? [1, 2] : [3, 4]),
        ]);

        $query->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'order_service_type_name', $this->order_service_type_name])
            ->andFilterWhere(['like', 'order_channel_name', $this->order_channel_name])
            ->andFilterWhere(['like', 'order_address', $this->order_address])
            ->andFilterWhere(['like', 'order_cs_memo', $this->order_cs_memo]);

        // 根据时间段查询
        if (!empty($this->created_from))
            $query->andFilterWhere(['>=', Order::tableName() . '.created_at', strtotime($this->created_from)]);

        if (!empty($this->created_to))
            $query->andFilterWhere(['<=', Order::tableName() . '.created_at', strtotime($this->created_to)]);

        if (!empty($this->assign_from))
            $query->andFilterWhere(['>=', Order::tableName() . '.order_worker_assign_time', strtotime($this->assign_from)]);

        if (!empty($this->assign_to))
            $query->andFilterWhere(['<=', Order::tableName() . '.order_worker_assign_time', strtotime($this->assign_to)]);

        if (!empty($this->booked_from))
            $query->andFilterWhere(['>=', 'order_booked_begin_time', strtotime($this->booked_from)]);

        if (!empty($this->booked_to))
            $query->andFilterWhere(['<=', 'order_booked_begin_time', strtotime($this->booked_to)]);

        // 根据电话同时查询指定阿姨及接单阿姨
        $query->andFilterWhere(['or', ['bookedWorker.worker_phone' => $this->order_worker_phone], ['order_worker_phone' => $this->order_worker_phone]]);

        // 筛选功能
        if (isset($this->cities)) {
            $cityList = explode('-', $this->cities);
            if (!empty($cityList)) {
                $query->andWhere(['in', 'city_id', $cityList]);
            }
        }

        if (!empty($this->statuss)) {
            $statussList = explode('-', $this->statuss);
            if (!empty($statussList)) {
                if (in_array(OrderStatusDict::ORDER_WORKER_BIND_ORDER, $statussList)) {
                    $statussList[] = OrderStatusDict::ORDER_SYS_ASSIGN_DONE;
                    $statussList[] = OrderStatusDict::ORDER_MANUAL_ASSIGN_DONE;
                }
                $query->andWhere(['in', 'order_status_dict_id', $statussList]);
            }
        }

        if (isset($this->types)) {
            $typeList = explode('-', $this->types);
            if (!empty($typeList)) {
                $query->andWhere(['in', 'order_service_type_id', $typeList]);
            }
        }

        if (isset($this->channels)) {
            $channelList = explode('-', $this->channels);
            if (!empty($channelList)) {
                $query->andWhere(['in', 'channel_id', $channelList]);
            }
        }


        return $dataProvider;
    }

}
