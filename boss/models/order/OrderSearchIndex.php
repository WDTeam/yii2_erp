<?php

namespace boss\models\order;

use core\models\order\Order;

use Yii;
use yii\data\ActiveDataProvider;

/*
 * 仅用于订单查询主页面!!!!!!!!!!!!!!!!!!!!!!!
 *   
 */
class OrderSearchIndex extends Order
{

    public function rules()
    {
        return [
            [['order_code', 'order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'isdel', 'order_ip', 'order_service_type_id', 'order_src_id', 'channel_id', 'order_booked_count', 'order_booked_begin_time', 'order_booked_end_time', 'address_id', 'order_booked_worker_id', 'checking_id', 'shop_id', 'district_id', 'city_id'], 'integer'],
            [['order_unit_money', 'order_money'], 'number'],
            [['order_channel_name'], 'string', 'max' => 64],
            [['order_customer_phone', 'order_worker_phone'], 'match', 'pattern' => '/^\d{11}$/i', 'message' => '请填写正确的电话号码或格式！(11位数字)'],
            [['order_service_type_name', 'order_src_name'], 'string', 'max' => 128],
            [['order_address', 'order_cs_memo'], 'string', 'max' => 255],
            [['order_status_dict_id'], 'safe'],
        ];
    }
    
    public function search($params)
    {
        $query = Order::find()->joinWith(['orderExtPop', 'orderExtCustomer', 'orderExtWorker', 'orderExtStatus', 'orderExtPay']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//             'pagination' => [
//                 'pageSize' => 2,
//             ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        
        $query->andFilterWhere([
            'id' => $this->id,
            'order_parent_id' => $this->order_parent_id,
            'order_is_parent' => $this->order_is_parent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'isdel' => $this->isdel,
            'order_ip' => $this->order_ip,
            'order_service_type_id' => $this->order_service_type_id,
            'order_src_id' => $this->order_src_id,
            'channel_id' => $this->channel_id,
            'order_unit_money' => $this->order_unit_money,
            'order_money' => $this->order_money,
            'order_booked_count' => $this->order_booked_count,
            'order_booked_begin_time' => $this->order_booked_begin_time,
            'order_booked_end_time' => $this->order_booked_end_time,
            'address_id' => $this->address_id,
            //'order_booked_worker_id' => $this->order_booked_worker_id,
            'checking_id' => $this->checking_id,
            'order_pop_order_code' => $this->order_pop_order_code,
            'order_customer_phone' => $this->order_customer_phone,
            //'order_worker_phone' => $this->order_worker_phone,
            'shop_id' => $this->shop_id,
            'district_id' => $this->district_id,
            'city_id' => $this->city_id,
            'order_status_dict_id' => $this->order_status_dict_id,
        ]);
        
        $query->orFilterWhere(['or', ['order_booked_worker_id' => $this->order_worker_phone], ['order_worker_phone' => $this->order_worker_phone]]);

        //两种特殊状态的订单查询条件是订单服务时间
        if (isset($this->order_status_dict_id) && is_array($this->order_status_dict_id) 
        && (in_array(-1, $this->order_status_dict_id) || in_array(-2, $this->order_status_dict_id))) {
            if (in_array(-1, $this->order_status_dict_id)) {

                //人工指派失败且服务时间在两小时内
                $two_hour_before = strtotime('+2 hour');
                $query->andFilterWhere(['>=', 'order_booked_begin_time', time()]);
                $query->andFilterWhere(['<=', 'order_booked_begin_time', $two_hour_before]);
            }

            if (in_array(-2, $this->order_status_dict_id)) {

                //人工指派失败且超过服务时间
                $query->andFilterWhere(['<=', 'order_booked_begin_time', time()]);
            }
        }

        $query->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'order_service_type_name', $this->order_service_type_name])
            ->andFilterWhere(['like', 'order_src_name', $this->order_src_name])
            ->andFilterWhere(['like', 'order_channel_name', $this->order_channel_name])
            ->andFilterWhere(['like', 'order_address', $this->order_address])
            ->andFilterWhere(['like', 'order_cs_memo', $this->order_cs_memo]);
                
        if (!empty($params['created_from']))
            $query->andFilterWhere(['>=', Order::tableName().'.created_at', strtotime($params['created_from'])]);

        if (!empty($params['created_to']))
            $query->andFilterWhere(['<=', Order::tableName().'.created_at', strtotime($params['created_to'])]);
        
        if (!empty($params['booked_from']))
            $query->andFilterWhere(['>=', 'order_booked_begin_time', strtotime($params['booked_from'])]);
        
        if (!empty($params['booked_to']))
            $query->andFilterWhere(['<=', 'order_booked_begin_time', strtotime($params['booked_to'])]);
        return $dataProvider;
    }
    
}
