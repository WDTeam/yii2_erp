<?php
/**
 * Created by PhpStorm.
 * User: LinHongYou
 * Date: 2015/9/23
 * Time: 18:30
 */

namespace core\models\order;

use Yii;
use yii\base\Model;
use common\models\Order;

class OrderService extends Order
{

    public function create($post)
    {

        if ($this->load($post)) {

            $this->setAttributes([
                'pay_parent_id' => 0,
                'order_is_parent' => 0,
                'order_before_status_dict_id' => 0,
                'order_before_status_name' => '初始化前',
                'order_status_dict_id' => 1,
                'order_status_dict_name' => '初始化',
                'order_flag_send' => 0, //'指派不了 0可指派 1客服指派不了 2小家政指派不了 3都指派不了',
                'order_flag_urgent' => 0,//加急 数字越大约紧急
                'order_flag_exception' => 0,//异常标识
                'order_src_id'=>1,
                'order_src_name'=>'BOSS',
                'channel_id'=>1,
                'order_channel_name'=>'BOSS',
                'order_channel_order_num'=>'',
                'order_lock_status'=>0,
                'worker_id'=>0,
                'worker_type_id'=>0,
                'order_worker_send_type'=>0,
                'comment_id'=>0,
                'order_customer_hidden'=>0,
                'order_pop_pay_money'=>0,
                'invoice_id'=>0, //发票id 用户需求中有开发票就绑定发票id
                'checking_id'=>0,
            ]);

            if ($this->save()) {
                $this->order_code = $this->id;
                $this->order_channel_order_num = $this->order_code;
                if ($this->save()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getById($id)
    {
        return $this->findOne($id);
    }
}