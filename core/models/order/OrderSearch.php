<?php

namespace core\models\order;

use common\models\order\OrderExtCustomer;
use common\models\order\OrderExtFlag;
use common\models\order\OrderExtStatus;
use common\models\order\OrderStatusDict;
use core\models\customer\Customer;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `common\models\order\Order`.
 */
class OrderSearch extends Order
{

    public function rules()
    {
        return [
            [['order_parent_id', 'order_is_parent', 'created_at', 'updated_at', 'isdel', 'order_ip', 'order_service_type_id', 'order_src_id', 'channel_id', 'order_booked_count', 'order_booked_begin_time', 'order_booked_end_time', 'address_id', 'order_booked_worker_id', 'checking_id', 'shop_id', 'district_id', 'city_id', 'order_status_dict_id'], 'integer'],
            [['order_unit_money', 'order_money'], 'number'],
            [['order_code', 'order_channel_name', 'order_customer_phone'], 'string', 'max' => 64],
            [['order_service_type_name', 'order_src_name'], 'string', 'max' => 128],
            [['order_address', 'order_cs_memo'], 'string', 'max' => 255],
        ];
    }

    /**
     * 通过阿姨ID获取指定月份的所有订单(包括结算状态)
     * @param $worker_id 阿姨ID
     * @param $month 指定月份
     */
    public static function getWorkerAndOrderAndMonth($worker_id,$year,$month=1,$day=1)
    {
        $year = !empty($year) ? $year : date("Y",time());
        //制造时间戳
        $month_begin = mktime(0,0,0,$month,$day,$year);
        $month_end = mktime(0,0,0,$month+1,$day,$year);
        $query = new \yii\db\Query();
        $data = $query->from('{{%order}} as order')
            ->innerJoin('{{%order_ext_status}} as os','order.id = os.order_id')
            ->innerJoin('{{%order_ext_customer}} as oc','order.id = oc.order_id')
            ->innerJoin('{{%order_ext_pay}} as op','order.id = op.order_id')
            ->innerJoin('{{%order_ext_worker}} as ow','order.id = ow.order_id')
            ->select('*')
            ->where(['ow.worker_id'=>$worker_id])
            ->andWhere(['between', 'order.created_at', $month_begin, $month_end])
            //->createCommand()->getRawSql();
            ->all();
        return $data;
    }

    /**
     * 通过订单ID获取带用户信息的订单
     * @param $order_id 订单ID
     */
    public static function getOrderAndCustomer($order_id)
    {
        $query = new \yii\db\Query();
        $data = $query->from('{{%order}} as order')
            ->innerJoin('{{%order_ext_status}} as os','order.id = os.order_id')
            ->innerJoin('{{%order_ext_customer}} as oc','order.id = oc.order_id')
            ->innerJoin('{{%order_ext_pay}} as op','order.id = op.order_id')
            ->innerJoin('{{%order_ext_worker}} as ow','order.id = ow.order_id')
            ->select('*')
            ->where(['id'=>$order_id])
            ->one();
        $data['customer'] = Customer::getCustomerById($data['customer_id'])->getAttributes();
        return $data;
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
            'orderExtStatus.order_status_dict_id' => OrderStatusDict::ORDER_MANUAL_ASSIGN_START,
            'orderExtFlag.order_flag_lock' => $admin_id
        ])->orderBy(['order_booked_begin_time' => SORT_ASC])->one();
        if (empty($order)) {//如果没有正在指派的订单再查询待指派的订单
            $order = Order::find()->joinWith(['orderExtStatus', 'orderExtFlag'])->where([
                'and',
                ['>', 'order_booked_begin_time', time()], //服务开始时间大于当前时间
                ['orderExtFlag.order_flag_send' => [0, $flag_send]] //0可指派 1客服指派不了 2小家政指派不了
            ])->andWhere([
                'or',
                ['orderExtFlag.order_flag_lock' => 0],
                ['<','orderExtFlag.order_flag_lock_time',time()-Order::MANUAL_ASSIGN_lONG_TIME] //查询超时未解锁的订单
            ])->andWhere([ //系统指派失败的 或者 已支付待指派并且标记不需要系统指派的订单
                'or',
                [
                    'orderExtStatus.order_status_dict_id' => OrderStatusDict::ORDER_SYS_ASSIGN_UNDONE
                ],
                [
                    'orderExtStatus.order_status_dict_id' => OrderStatusDict::ORDER_WAIT_ASSIGN,
                    'orderExtFlag.order_flag_sys_assign' => 0
                ]
            ])->orderBy(['order_booked_begin_time' => SORT_ASC])->one();
            if (!empty($order)) {
                //获取到订单后加锁并置为已开始人工派单的状态
                $order->order_flag_lock = $admin_id;
                $order->order_flag_lock_time = time(); //加锁时间
                $order->order_flag_send = $order->orderExtFlag->order_flag_send + ($isCS ? 1 : 2); //指派时先标记是谁指派不了
                $order->admin_id = $admin_id;
                if (OrderStatus::manualAssignStart($order, ['OrderExtFlag'])) {
                    OrderPool::remOrderForWorkerPushList($order->id); //从接单大厅中删除此订单
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
     * @return int
     */
    public static function WorkerOrderExistsConflict($worker_id, $booked_begin_time, $booked_end_time)
    {
        return Order::find()->joinWith(['orderExtWorker'])->where(['worker_id' => $worker_id])
            ->andWhere(
                ['or',
                    [
                        'and',
                        ['<=', 'order_booked_begin_time', $booked_begin_time],
                        ['>=', 'order_booked_end_time', $booked_begin_time]
                    ],
                    [
                        'and',
                        ['<=', 'order_booked_begin_time', $booked_end_time],
                        ['>=', 'order_booked_end_time', $booked_end_time]
                    ],
                    [
                        'and',
                        ['>=', 'order_booked_begin_time', $booked_begin_time],
                        ['<=', 'order_booked_end_time', $booked_end_time]
                    ]
                ]

            )->count();
    }

    /**
     * 返回推送给阿姨的订单列表
     * @param $worker_id
     * @param int $page_size
     * @param int $page
     * @param bool $is_booked
     * @return mixed
     */
    public static function getPushWorkerOrders($worker_id,$page_size=20,$page=1,$is_booked)
    {
        return OrderPool::getOrdersFromWorkerPushList($worker_id,$page_size,$page,$is_booked);
    }

    /**
     * 返回推送给阿姨的订单总数
     * @param $worker_id
     * @param bool $is_booked
     * @return mixed
     */
    public static function getPushWorkerOrdersCount($worker_id,$is_booked)
    {
        return OrderPool::getOrdersCountFromWorkerPushList($worker_id,$is_booked);
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
    public function searchOrdersWithStatus($attributes, $is_asc = false, $offset = 1, $limit = 10, $order_status = null,$channels = null, $from = null, $to = null)
    {
        $sort = $is_asc ? SORT_ASC : SORT_DESC;
        $params['OrderSearch'] = $attributes;
        $query = $this->searchOrdersWithStatusProvider($params,$order_status,$channels,$from,$to)->query;
        $query->orderBy(['os.created_at' => $sort]);
        $query->offset($offset)->limit($limit);
        return $query->all();
    }


    /**
    * 分页查询带状态订单数量
    * @param $customer_id
    * @return int|string
    */
    public function searchOrdersWithStatusCount($attributes,  $order_status = null,$channels=null,$from=null,y$to=null)
    {
        $params['OrderSearch'] = $attributes;
        $query = $this->searchOrdersWithStatusProvider($params,$order_status,$channels,$from,$to)->query;
        return $query->count();
    }

    /**
     *
     * 依据订单状态 查询带状态的用户订单query对象
     * @return
     */
    public function searchOrdersWithStatusProvider($attributes, $order_status = null,$channels = null, $from = null, $to = null)
    {
        $query = new \yii\db\Query();

        $query->from('{{%order}} as order')->innerJoin('{{%order_ext_status}} as os','order.id = os.order_id')->innerJoin('{{%order_ext_customer}} as oc','order.id = oc.order_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query = $query->select(['*']);
        if (!is_null($from) && is_numeric($from)) {
            $query->andFilterWhere(['>', 'order_booked_begin_time', $from]);
        }
        if (!is_null($to) && is_numeric($to)) {
            $query->andFilterWhere(['<', 'order_booked_begin_time', $to]);
        }
        if (!is_null($order_status)) {
                foreach($order_status as $status_str){
                    $query = $query->orFilterWhere([
                        'os.order_status_dict_id' => $status_str
                    ]);
                }
        }

        if (!is_null($channels)) {
            foreach($channels as $channels_str){
                $query = $query->orFilterWhere([
                    'channel_id' => $channels_str
                ]);
            }
        }

        if ($this->load($attributes) && $this->validate()) {
            $query->andFilterWhere([
                'id' => $this->id,
                'order_parent_id' => $this->order_parent_id,
                'order_is_parent' => $this->order_is_parent,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'isdel' => $this->isdel,
                'worker_id' => $this->worker_id,
                'order_ip' => $this->order_ip,
                'order_service_type_id' => $this->order_service_type_id,
                'order_src_id' => $this->order_src_id,
                'order_unit_money' => $this->order_unit_money,
                'order_money' => $this->order_money,
                'order_booked_count' => $this->order_booked_count,
                'order_booked_begin_time' => $this->order_booked_begin_time,
                'order_booked_end_time' => $this->order_booked_end_time,
                'address_id' => $this->address_id,
                'order_booked_worker_id' => $this->order_booked_worker_id,
                'checking_id' => $this->checking_id,
                'order_pop_order_code' => $this->order_pop_order_code,
                'oc.customer_id' => $attributes["OrderSearch"]["oc.customer_id"],
            ]);
            $query->andFilterWhere(['like', 'order_service_type_name', $this->order_service_type_name]
            );
        }
        return $dataProvider;
    }

    public function searchList($attributes)
    {
        $params['OrderSearch'] = $attributes;
        return $this->search($params)->query->all();
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
            'order_booked_worker_id' => $this->order_booked_worker_id,
            'checking_id' => $this->checking_id,
            'order_pop_order_code' => $this->order_pop_order_code,
            'order_customer_phone' => $this->order_customer_phone,
            'worker_phone' => $this->order_worker_phone,
            'shop_id' => $this->shop_id,
            'district_id' => $this->district_id,
            'city_id' => $this->city_id,
            'order_status_dict_id' => $this->order_status_dict_id,
        ]);

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
            $query->andFilterWhere(['<=', 'order_booked_end_time', strtotime($params['booked_to'])]);
        return $dataProvider;
    }
    
	/**
	* 第三方对账专用
	* @date: 2015-10-23
	* @author: peak pan
	* @return:
	**/
    
    public  $finance_record_log_endtime;
    
    public function searchpoplist()
    {
    	$query = Order::find()->joinWith(['orderExtPop']);
    	$dataProvider = new ActiveDataProvider([
    			'query' => $query,
    			]);

    	$query->andFilterWhere([
    			'id' => $this->id,
    			'order_parent_id' => $this->order_parent_id,
    			'order_is_parent' => $this->order_is_parent,
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

    			]);
    
    	$query->andFilterWhere(['like', 'order_code', $this->order_code])
    	->andFilterWhere(['like', 'order_service_type_name', $this->order_service_type_name])
    	->andFilterWhere(['between', 'ejj_order.created_at', $this->created_at,$this->finance_record_log_endtime])
    	->andFilterWhere(['not in', 'orderExtPop.order_pop_order_code', $this->order_pop_order_code])
    	->andFilterWhere(['like', 'order_src_name', $this->order_src_name])
    	->andFilterWhere(['like', 'order_channel_name', $this->order_channel_name])
    	->andFilterWhere(['like', 'order_address', $this->order_address])
    	->andFilterWhere(['like', 'order_cs_memo', $this->order_cs_memo]);
    
    	return $dataProvider;
    }
    
    
}
