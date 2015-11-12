<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use core\models\order\OrderStatusDict;
use boss\models\worker\Worker;
use boss\models\order\Order;

?>
<style>
    #m_warp .m_from table tr th {
        background: #f3f3f3;
        padding: 10px 0;
        border: 0 !important;
    }

    span {
        color: #000;
    }

    .m_tab th ul,.m_tab th ul li {
        display: inline-block;
        margin-right: 10px;
        text-align: center;
    }

</style>
<div class="m_tab">
    <table cellspacing="0" cellpadding="0" border="1">
        <tr class="first">
            <th colspan="5">订单编号：<?= Html::encode($model->order_code) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                服务项目：<?= Html::encode($model->order_service_item_name) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <ul>
                <?php
                    foreach($model->orderStatusHistory as $v){
                        echo "<li style=\"color:#f6a202\">{$v['order_status_boss']} (".date("m-d H:i",$v['created_at']).")</li>";
                    }
                ?>
                </ul>
            </th>
        </tr>
        <tr>
            <input type="hidden" class="order_id" value="<?= Html::encode($model->id) ?>"/>
            <input type="hidden" class="order_code" value="<?= Html::encode($model->order_code) ?>"/>
            <input type="hidden" class="customer_phone"
                   value="<?= Html::encode($model->orderExtCustomer->order_customer_phone) ?>"/>
            <td style="width: 28%;">
                客户电话：<span><?= Html::encode($model->orderExtCustomer->order_customer_phone) ?></span><br/>
                客户身份：<span><?= $model->orderExtCustomer->order_customer_is_vip == 1 ? '会员' : '非会员' ?></span><br/>
                下单渠道：<span><?= Html::encode($model->order_channel_name) ?></span><br/>
                服务时间：<span><?= $model->getOrderBookedDate() . ' ' . $model->getOrderBookedTimeArrange() ?></span><br/>
                客户地址：<span><?= HtmlPurifier::process($model->order_address) ?></span>
            </td>
            <td>
                总金额：<span><?= Html::encode($model->order_money) ?>元</span><br/>
                优惠券：<span><?= Html::encode($model->orderExtPay->order_use_coupon_money) ?>元</span><br/>
                需支付：<span><?= Html::encode($model->orderExtPay->order_pay_money) ?>元</span><br/>
                支付渠道：<span><?= Html::encode($model->orderExtPay->order_pay_channel_name)?></span>
            </td>
            <td>
                <?php
                if ($model->orderExtWorker->worker_id > 0) {
                    echo '接单阿姨：<span>' . $model->orderExtWorker->order_worker_name . '</span><br />';
                    echo '阿姨手机：<span>' . $model->orderExtWorker->order_worker_phone . '</span><br />';
                    echo '阿姨身份：<span>' . $model->orderExtWorker->order_worker_type_name . '</span><br />';
                    echo '所属门店：<span>' . $model->orderExtWorker->order_worker_shop_name . '</span>';
                } else {
                    if (!empty($model->order_booked_worker_id)) {
                        $worker = Worker::findOne($model->order_booked_worker_id);
                        echo '指定阿姨：<span>' . $worker['worker_name'] . '</span><br />';
                        echo '阿姨手机：<span>' . $worker['worker_phone'] . '</span><br />';
                        echo '阿姨身份：<span>' . Worker::getWorkerTypeShow($worker['worker_type']) . '</span><br />';
                        echo '所属门店：<span>' . Worker::getShopName($worker['shop_id']) . '</span>';
                    }
                }
                ?>
            </td>
            <td>
                下单时间：<span><?= date('Y-m-d H:i', $model->created_at) ?></span><br/>
                <?php if ($model->orderExtStatus->order_status_dict_id != OrderStatusDict::ORDER_INIT): ?>
                    支付时间：<span><?= date('Y-m-d H:i', $model->orderExtPay->created_at) ?></span>
                <?php endif; ?>
                <?php if ($model->orderExtWorker->order_worker_assign_type > 0 and $model->orderExtWorker->order_worker_assign_type <= 2): ?>
                    指定时间：<span><?= date('Y-m-d H:i', $model->orderExtWorker->created_at) ?></span>
                <?php elseif ($model->orderExtWorker->order_worker_assign_type > 2): ?>
                    接单时间：<span><?= date('Y-m-d H:i', $model->orderExtWorker->created_at) ?></span>
                <?php endif; ?>
            </td>
            <td>
                <p><a href="/order/order/edit?id=<?= Html::encode($model->order_code) ?>">查看订单</a></p>

                <?php if ($model->orderExtStatus->order_status_dict_id != OrderStatusDict::ORDER_INIT): ?>
                    <p><a href="javascript:void(0);" class="m_tousu">投诉</a></p>
                <?php endif; ?>
                <!-- <p><a href="###">发送短信</a></p> -->

                <?php if ($model->orderExtStatus->order_status_dict_id != OrderStatusDict::ORDER_CANCEL): ?>
                    <p><a href="javascript:void(0);" class="m_quxiao">取消订单</a></p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</div>