<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace boss\models\order;

use core\models\customer\Customer;
use core\models\order\OrderSrc;
use core\models\order\OrderWorkerRelation;
use dbbase\models\finance\FinanceOrderChannel;
use core\models\operation\OperationArea;
use core\models\order\OrderPay;
use Yii;
use core\models\order\Order as OrderModel;
use core\models\worker\Worker;
use yii\helpers\ArrayHelper;
use core\models\operation\OperationCity;
use core\models\order\OrderStatusDict;


class Order extends OrderModel
{

    public $orderBookedDate;
    public $orderBookedTimeRange;

    public function rules()
    {
        return array_merge([
            [['order_customer_phone','orderBookedDate','orderBookedTimeRange'],'required'],
            [['order_customer_phone'],'match','pattern'=>'%^1[3-9]\d{9}$%','message'=>'请填写正确格式的手机号码'],   //手机号码验证

        ],parent::rules());
    }

    public static function getWorkerInfoByPhone($phone)
    {
        return Worker::getWorkerInfoByPhone($phone);
    }


    public function getOrderBookedCountList()
    {
        return ["2" => "两小时", "2.5" => "两个半小时", "3" => "三小时", "3.5" => "三个半小时", "4" => "四小时", "4.5" => "四个半小时", "5" => "五小时", "5.5" => "五个半小时", "6" => "六小时"];
    }

    public function getCustomerNeeds()
    {
        return [
            '重点打扫厨房'=>'重点打扫厨房',
            '重点打扫卫生间'=>'重点打扫卫生间',
            '家有爱宠'=>'家有爱宠',
            '上门前打个电话'=>'上门前打个电话',
            '很久没打扫了'=>'很久没打扫了',
            '阿姨不要很多话'=>'阿姨不要很多话'
        ];
    }

    public static function getStatusDictList($status_id = 0)
    {
        return OrderStatusDict::getBossStatusDictList($status_id);
    }

    public function getStatusHistoryTime()
    {
        return ArrayHelper::map($this->orderStatusHistory, 'order_status_dict_id', 'created_at');
    }

    /**
     * TODO 获取开通省份列表
     * @return array
     */
    public function getOnlineProvinceList(){
        $province_list = OperationCity::find()->select(['province_id','province_name'])->where(['operation_city_is_online'=>1])->groupBy(['province_id'])->all();
        return ArrayHelper::map($province_list,'province_id','province_name');
    }
    /**
     * 获取已开通城市列表
     * @return array
     */
    public static function getOnlineCityList($province_id=null){
        if ($province_id)
            $onlineCityList = OperationCity::getCityOnlineInfoListByProvince($province_id);
        else
            $onlineCityList = OperationCity::getCityOnlineInfoList();
        return $onlineCityList?ArrayHelper::map($onlineCityList,'city_id','city_name'):[];
    }

    public static function getCountyList($city_id)
    {
        $county_list = OperationArea::getAreaList($city_id);
        $countys = [];
        if(is_array($county_list)) {
            foreach ($county_list as $k => $v) {
                $county = explode('_', $k);
                $countys[$county[0]] = $v;
            }
        }
        return $countys;
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return parent::attributeLabels()+[
            'orderBookedDate' => '预约服务日期',
            'orderBookedTimeRange' => '预约服务时间',
        ];
    }

    /**
     * 获取订单渠道
     * @param int $channel_id
     * @return array|bool
     */
    public function getOrderChannelList($channel_id = 0)
    {
        $list = FinanceOrderChannel::get_order_channel_list_info();
        $channel = ArrayHelper::map($list, 'id', 'finance_order_channel_name');
        return $channel_id == 0 ? $channel : (isset($channel[$channel_id]) ? $channel[$channel_id] : false);
    }

    /**
     * 可指派的阿姨格式化
     * @param $order
     * @param $worker_list
     * @return array
     */
    public static function assignWorkerFormat($order,$worker_list){
        //获取常用阿姨
        $used_worker_list = Customer::getCustomerUsedWorkers($order->orderExtCustomer->customer_id);
        $used_worker_ids = [];
        if (is_array($used_worker_list)) {
            foreach ($used_worker_list as $v) {
                $used_worker_ids[] = $v['worker_id'];
            }
        }
        $worker_ids = [];
        $workers = [];
        if (is_array($worker_list)) {
            foreach ($worker_list as $k => $v) {
                $worker_ids[] = $v['id'];
                $workers[$v['id']] = $worker_list[$k];
                $workers[$v['id']]['tag'] = in_array($v['id'], $used_worker_ids) ? '服务过' : "";
                $workers[$v['id']]['tag'] = ($v['id'] == $order->order_booked_worker_id) ? '指定阿姨' : $workers[$v['id']]['tag'];
                $workers[$v['id']]['order_booked_time_range'] = [];
                $workers[$v['id']]['memo'] = [];
                $workers[$v['id']]['status'] = [];
            }
            //获取阿姨当天订单
            $worker_orders = OrderSearch::getListByWorkerIds($worker_ids, $order->order_booked_begin_time);
            foreach ($worker_orders as $v) {
                $workers[$v->orderExtWorker->worker_id]['order_booked_time_range'][] = date('H:i', $v->order_booked_begin_time) . '-' . date('H:i', $v->order_booked_end_time);
            }
            //获取阿姨跟订单的关系
            $order_worker_relations = OrderWorkerRelation::getListByOrderIdAndWorkerIds($order->id, $worker_ids);
            foreach ($order_worker_relations as $v) {
                $workers[$v->worker_id]['memo'][] = $v->order_worker_relation_memo;
                $workers[$v->worker_id]['status'][] = $v->order_worker_relation_status;
            }
        }
        return $workers;
    }

    public function createNew($post)
    {
        $post['Order']['admin_id'] = Yii::$app->user->id;
        $post['Order']['order_ip'] = Yii::$app->request->userIP;
        $post['Order']['order_src_id'] = OrderSrc::ORDER_SRC_BOSS; //下单方式BOSS
        $post['Order']['order_is_use_balance'] =  $post['Order']['order_pay_type']==2?1:0; //是否使用余额
        $post['Order']['channel_id'] = empty($post['Order']['channel_id'])?20:$post['Order']['channel_id']; //订单渠道
        $post['Order']['order_customer_need'] = (isset($post['Order']['order_customer_need']) && is_array($post['Order']['order_customer_need']))?implode(',',$post['Order']['order_customer_need']):''; //客户需求
        //预约时间处理
        if(empty($post['Order']['orderBookedTimeRange'])){
            $this->addError('orderBookedTimeRange','预约服务时间不能不选！');
            return false;
        }
        $time = explode('-',$post['Order']['orderBookedTimeRange']);
        $post['Order']['order_booked_begin_time'] = strtotime($post['Order']['orderBookedDate'].' '.$time[0].':00');
        $post['Order']['order_booked_end_time'] = strtotime(($time[1]=='24:00')?date('Y-m-d H:i:s',strtotime($post['Order']['orderBookedDate'].'00:00:00 +1 days')):$post['Order']['orderBookedDate'].' '.$time[1].':00');
        return parent::createNew($post['Order']);
    }
}