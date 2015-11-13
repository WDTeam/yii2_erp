<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace boss\models\order;

use core\models\customer\Customer;
use core\models\operation\OperationOrderChannel;
use core\models\operation\OperationPayChannel;
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
        return ["2.0" => "两小时", "2.5" => "两个半小时", "3.0" => "三小时", "3.5" => "三个半小时", "4.0" => "四小时", "4.5" => "四个半小时",
            "5.0" => "五小时", "5.5" => "五个半小时", "6.0" => "六小时"];
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
     * @return array|bool
     */
    public function getOrderChannelList()
    {
        $list = OperationOrderChannel::getorderchannellist(3); //boss 使用的渠道列表
        $channels = ['后台下单'=>'后台下单'];
        foreach($list as $v){
            $channels[$v] = $v;
        }
        return $channels;
    }

    public function getEjjPayChannelList()
    {
        $list = OperationPayChannel::getpaychannellist(2);
        unset($list[1]);
        return $list;
    }


    public function createNew($post)
    {
        $post['Order']['admin_id'] = Yii::$app->user->id;
        $post['Order']['order_ip'] = Yii::$app->request->userIP;
        $post['Order']['order_is_use_balance'] = 1; //使用余额
        $post['Order']['channel_id'] = $post['Order']['channel_id']; //订单渠道
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