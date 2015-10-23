<?php

namespace core\models\order;

use common\models\OrderExtCustomer;
use common\models\OrderExtFlag;
use common\models\OrderExtStatus;
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
     * @param $admin_id 操作人id
     * @param $isCS bool 是否是客服获取
     * @return $this|static
     */
    public static function getWaitManualAssignOrder($admin_id, $isCS = false)
    {
        $flag_send = $isCS ? 2 : 1;

        $order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where(
            ['>', 'order_booked_begin_time', time()] //服务开始时间大于当前时间
        )->andWhere([ //先查询该管理员正在指派的订单
            'orderExtStatus.order_status_dict_id'=>OrderStatusDict::ORDER_MANUAL_ASSIGN_START,
            'orderExtFlag.order_flag_lock'=>$admin_id
        ])->orderBy(['order_booked_begin_time'=>SORT_ASC])->one();
        if(empty($order)){//如果没有正在指派的订单再查询待指派的订单
            $order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where(
                ['>', 'order_booked_begin_time', time()] //服务开始时间大于当前时间
            )->andWhere([
                'orderExtFlag.order_flag_send'=>[0,$flag_send], //0可指派 1客服指派不了 2小家政指派不了
                'orderExtFlag.order_flag_lock'=>0,
            ])->andWhere([ //系统指派失败的 或者 已支付待指派并且标记不需要系统指派的订单
                'or',
                [
                    'orderExtStatus.order_status_dict_id'=>OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE
                ],
                [
                    'orderExtStatus.order_status_dict_id'=>OrderStatusDict::ORDER_WAIT_ASSIGN,
                    'orderExtFlag.order_flag_sys_assign'=>0
                ]
            ])->orderBy(['order_booked_begin_time'=>SORT_ASC])->one();
            if(!empty($order)){
                //获取到订单后加锁并置为已开始人工派单的状态
                $order->order_flag_lock = $admin_id;
                $order->order_flag_lock_time = time(); //加锁时间
                $order->order_flag_send = $order->orderExtFlag->order_flag_send+($isCS?1:2); //指派时先标记是谁指派不了
                $order->admin_id = $admin_id;
                if (OrderStatus::manualAssignStart($order, ['OrderExtFlag'])) {
                    return Order::findOne($order->id);
                }
            }
        } else {
            return $order;
        }
        return false;
    }

    /**
     * 根据预约开始时间获取多个阿姨当天订单
     * @param $worker_ids
     * @param $booked_begin_time
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getListByWorkerIds($worker_ids, $booked_begin_time)
    {
        $day_begin = strtotime(date('Y:m:d 00:00:00', $booked_begin_time));
        $day_end = strtotime(date('Y:m:d 23:59:59', $booked_begin_time));
        return Order::find()->joinWith(['orderExtWorker'])->where(['worker_id' => $worker_ids])->andWhere(['between', 'order_booked_begin_time', $day_begin, $day_end])->all();
    }

    /**
     * 根据预约的开始时间获取单个阿姨当天订单
     * @param $worker_id
     * @param $booked_begin_time
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getListByWorkerId($worker_id, $booked_begin_time)
    {
        return self::getListByWorkerIds([$worker_id], $booked_begin_time);
    }

    /**
     * 是否存在冲突订单
     * @param $worker_id
     * @param $booked_begin_time
     * @param $booked_end_time
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function WorkerOrderExistsConflict($worker_id,$booked_begin_time,$booked_end_time)
    {
        return Order::find()->joinWith(['orderExtWorker'])->where(['worker_id' => $worker_id])
            ->andWhere([
                'or',
                [
                    ['<=','order_booked_begin_time',$booked_begin_time],
                    ['>=','order_booked_end_time',$booked_begin_time]
                ],
                [
                    ['<=','order_booked_begin_time',$booked_end_time],
                    ['>=','order_booked_end_time',$booked_end_time]
                ],
                [
                    ['>=','order_booked_begin_time',$booked_begin_time],
                    ['<=','order_booked_end_time',$booked_end_time]
                ],
            ])->count();
    }

    /**
     * 获取客户订单数量
     * @param $customer_id
     * @return int|string
     */
    public static function getCustomerOrderCount($customer_id)
    {
        return OrderExtCustomer::find()->where(['customer_id' => $customer_id])->count();
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public static function getOne($id)
    {
        return Order::findOne($id);
    }

     /**
     * 分页查询带状态订单
     * @param $attributes
     * @return int|string
     */
    public function searchOrdersWithStatus($attributes, $is_asc = false, $offset = 1, $limit = 10, $order_status = null, $from = null, $to = null)
    {
        $sort = $is_asc ? SORT_AESC : SORT_DESC;
        $params['OrderSearch'] = $attributes;
        $query = $this->search($params)->query;
        $query->orderBy(['created_at' => $sort]);
        $query->offset($offset)->limit($limit);
        return $query->all();
    }

    /**
    * 分页查询带状态订单数量
    * @param $customer_id
    * @return int|string
    */
    public function searchOrdersWithStatusCount($attributes, $is_asc = false, $offset = 1, $limit = 10, $order_status = null, $from = null, $to = null)
    {
        $sort = $is_asc ? SORT_AESC : SORT_DESC;
        $params['OrderSearch'] = $attributes;
        $query = $this->search($params)->query;
        return $query->count();
    }

    /**
     *
     * 依据订单状态 查询带状态的用户订单query对象
     * @return
     */
    public function searchOrdersWithStatusProvider($attributes,  $order_status = null, $from = null, $to = null)
    {

        $params['OrderSearch'] = $attributes;
        $query = Order::find()->joinWith(['orderExtPop', 'orderExtStatus']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!is_null($from) && is_numeric($from)) {
            $query->andFilterWhere(['>', 'order_booked_begin_time', $from]);
        }
        if (!is_null($to) && is_numeric($to)) {
            $query->andFilterWhere(['<', 'order_booked_begin_time', $to]);
        }
        if (isSet($order_status)) {
            $query = $query->andFilterWhere([
                'orderExtStatus.order_status_dict_id' => $order_status
            ]);
        }
        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere([
                'id' => $this->id,
                'order_parent_id' => $this->order_parent_id,
                'order_is_parent' => $this->order_is_parent,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'isdel' => $this->isdel,
                'worker_id' =>$this->worker_id,
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
                'order_pop_order_code' => $this->order_pop_order_code,
                'customer_id' => $this->customer_id,
            ]);
            $query = $query->andFilterWhere(['like', 'order_service_type_name', $this->order_service_type_name]
            );
        }
        return $query;
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
            'order_pop_order_code' => $this->order_pop_order_code,
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
