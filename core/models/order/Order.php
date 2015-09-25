<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace core\models\order;

use Yii;
use common\models\Order as OrderModel;
use common\models\OrderHistory;
use common\models\OrderStatusHistory;
use common\models\OrderStatusDict;
use common\models\OrderSrc;

class Order extends OrderModel
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_code'],'unique'],
            [['order_code','order_service_type_id','customer_id', 'order_ip', 'address_id','order_unit_money', 'order_money','order_before_status_name', 'order_status_name', 'order_service_type_name',
                'order_src_name', 'order_address', 'order_customer_phone','order_booked_begin_time', 'order_booked_end_time'],'required'],
            [['order_parent_id', 'order_is_parent', 'order_before_status_dict_id', 'order_status_dict_id', 'order_flag_send', 'order_flag_urgent', 'order_flag_exception', 'order_service_type_id', 'order_src_id',
                'channel_id', 'customer_id', 'order_ip', 'order_booked_count', 'address_id', 'order_booked_worker_id', 'order_pay_type', 'pay_channel_id', 'card_id', 'coupon_id', 'promotion_id', 'order_lock_status',
                'worker_id', 'worker_type_id', 'order_worker_send_type', 'shop_id', 'comment_id', 'order_customer_hidden', 'invoice_id', 'checking_id', 'admin_id', 'isdel'], 'integer'],
            [['order_unit_money', 'order_money', 'order_pay_money', 'order_use_acc_balance', 'order_use_card_money', 'order_use_coupon_money', 'order_use_promotion_money', 'order_pop_pay_money'], 'number'],
            [['order_code', 'order_channel_name', 'order_worker_type_name'], 'string', 'max' => 64],
            [['order_before_status_name', 'order_status_name', 'order_service_type_name', 'order_src_name', 'order_pay_channel_name'], 'string', 'max' => 128],
            [['order_channel_order_num', 'order_address', 'order_customer_need', 'order_customer_memo', 'order_cs_memo', 'order_pay_flow_num'], 'string', 'max' => 255],
            [['order_customer_phone'], 'string', 'max' => 16],
            [['order_booked_begin_time', 'order_booked_end_time'], 'date', 'format'=>'yyyy-MM-dd HH:mm:ss'],

        ];
    }



    /**
     * 追加新订单
     * @param $post
     * @return bool
     */
    public function additional($post)
    {
        if($post['order']['order_parent_id'] <= 0) {
            $this->addError('order_parent_id','追加订单必须指定主订单编号！');
        }else{
            $post['order']['order_is_parent'] = 1;
            return $this->create($post);
        }
    }

    /**
     * 创建新订单
     * @param $post
     * @return bool
     */
    public function createNew($post)
    {
        $post['Order']['order_parent_id'] = 0;
        $post['Order']['order_is_parent'] = 0;
        return $this->create($post);
    }

    /**
     * 创建订单
     * @param $post
     * @return bool
     */
    public function create($post)
    {
        if ($this->load($post)) {
            $statusFrom = OrderStatusDict::findOne(OrderStatusDict::ORDER_CREATE); //创建订单状态
            $statusTo = OrderStatusDict::findOne(OrderStatusDict::ORDER_INIT); //初始化订单状态
            $orderSrc = OrderSrc::findOne($this->order_src_id);
            $orderCode = date('ymdHis').str_pad($this->order_service_type_id,2,'0',STR_PAD_LEFT).str_pad($this->customer_id,10,'0',STR_PAD_LEFT); //TODO 订单号待优化
            $this->setAttributes([
                'order_code'=>$orderCode,
                'order_before_status_dict_id' => $statusFrom->id,
                'order_before_status_name' => $statusFrom->order_status_name,
                'order_status_dict_id' => $statusTo->id,
                'order_status_dict_name' => $statusTo->order_status_name,
                'order_src_id' => $orderSrc->id,
                'order_src_name' => $orderSrc->order_src_name,

                'order_flag_send' => 0, //'指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
                'order_flag_urgent' => 0,//加急 数字越大约紧急
                'order_flag_exception' => 0,//异常标识
                'channel_id' => 0,
                'order_channel_name' => '',
                'order_channel_order_num' => '',
                'order_lock_status' => 0,
                'worker_id' => 0,
                'worker_type_id' => 0,
                'order_worker_send_type' => 0,
                'comment_id' => 0,
                'order_customer_hidden' => 0,
                'order_pop_pay_money' => 0,
                'invoice_id' => 0, //发票id 用户需求中有开发票就绑定发票id
                'checking_id' => 0,
                'isdel' => 0,
            ]);
            if ($this->doSave(true)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 保存订单
     * 保存时记录订单历史
     * @param bool $statusChanged
     * @return bool
     */
    public function doSave($statusChanged = false)
    {
        $transaction = static::getDb()->beginTransaction(); //开启一个事务
        if ($this->validate($this->attributes)) {
            $this->order_booked_begin_time = strtotime($this->order_booked_begin_time);
            $this->order_booked_end_time = strtotime($this->order_booked_end_time);
            if ($this->save(false)) {
                $attributes = $this->attributes;
                foreach ($attributes as $k => $v) {
                    $attributes[$k] = ($v === null) ? '' : $v;
                }
                $attributes['order_id'] = $attributes['id'];
                $attributes['order_created_at'] = $attributes['created_at'];
                $attributes['order_updated_at'] = $attributes['updated_at'];
                $attributes['order_isdel'] = $attributes['isdel'];
                unset($attributes['id']);
                unset($attributes['created_at']);
                unset($attributes['updated_at']);
                unset($attributes['isdel']);

                //插入订单操作历史
                $orderHistory = new OrderHistory();
                $orderHistory->setAttributes($attributes);
                if ($statusChanged) { //订单状态有改动
                    //插入订单状态变更历史
                    $orderStatusHistory = new OrderStatusHistory();
                    $orderStatusHistory->setAttributes($attributes);
                    if (!$orderStatusHistory->save()) {
                        $transaction->rollBack(); //插入不成功就回滚事务
                        return false;
                    }
                }
                if (!$orderHistory->save()) {
                    $transaction->rollBack();//插入不成功就回滚事务
                } else {
                    $transaction->commit();
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 修改订单状态
     * @param $from OrderStatusDict
     * @param $to OrderStatusDict
     */
    public function statusChange($from, $to)
    {
        $this->setAttributes([
            'order_before_status_dict_id' => $from->id,
            'order_before_status_name' => $from->$order_status_name,
            'order_status_dict_id' => $to->id,
            'order_status_dict_name' => $to->order_status_name
        ]);
        $this->doSave(true);
    }

    /**
     * 根据订单id获取订单
     * @param $id
     * @return null|static
     */
    public static function findById($id)
    {
        return self::findOne($id);
    }

}