<?php

namespace core\models\order;

use common\models\OrderStatusDict;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{

    public function rules()
    {
        return [
            [['order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'isdel', 'order_ip', 'order_service_type_id', 'order_src_id', 'channel_id', 'order_booked_count', 'order_booked_begin_time', 'order_booked_end_time', 'address_id', 'order_booked_worker_id', 'checking_id'], 'integer'],
            [['order_unit_money', 'order_money'], 'number'],
            [['order_code', 'order_channel_name'], 'string', 'max' => 64],
            [['order_service_type_name', 'order_src_name'], 'string', 'max' => 128],
            [['order_address', 'order_cs_memo'], 'string', 'max' => 255],
        ];
    }
    /**
     * 获取待人工指派的订单
     * 订单状态为系统指派失败的订单
     * @param $isCS bool 是否是客服获取
     * @param $admin_id 操作人id
     * @return $this|static
     */
    public static function getWaitManualAssignOrder($isCS = false,$admin_id)
    {
        $flag_send = $isCS?2:1;
        $order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where(
            ['>','order_booked_begin_time',time()] //服务开始时间大于当前时间
        )->andWhere([
            'orderExtStatus.order_status_dict_id'=>OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE,
            'orderExtFlag.order_flag_send'=>[0,$flag_send], //0可指派 1客服指派不了 2小家政指派不了
            'orderExtFlag.order_flag_lock'=>0, //0未锁定
        ])->orderBy(['order_booked_begin_time'=>'ASC'])->one();
        if(!empty($order)){
            //获取到订单后加锁
            $order->order_flag_lock = 1;
            $order->admin_id = $admin_id;
            if($order->doSave(['orderExtFlag'])){
                return $order;
            }
        }
        return false;
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function searchList($attributes)
    {
        $params['OrderSearch'] = $attributes;
        return $this->search($params)->query->all();
    }
    public function search($params)
    {
        $query = Order::find()->joinWith(['orderExtPop']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'order_booked_worker_id' => $this->order_booked_worker_id,
            'checking_id' => $this->checking_id,
            'order_pop_order_code'=>$this->order_pop_order_code,
        ]);

        $query->andFilterWhere(['like', 'order_code', $this->order_code])
            ->andFilterWhere(['like', 'order_service_type_name', $this->order_service_type_name])
            ->andFilterWhere(['like', 'order_src_name', $this->order_src_name])
            ->andFilterWhere(['like', 'order_channel_name', $this->order_channel_name])
            ->andFilterWhere(['like', 'order_address', $this->order_address])
            ->andFilterWhere(['like', 'order_cs_memo', $this->order_cs_memo]);

        return $dataProvider;
    }
}
