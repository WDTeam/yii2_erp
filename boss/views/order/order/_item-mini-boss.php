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
<?php $order_worker_assign_type = [0=>'',1=>'系统分单',2=>'系统分单',3=>'阿姨抢单',4=>'阿姨抢单'];?>
<div class="m_tab">
    <table cellspacing="0" cellpadding="0" border="1">
        <tr class="first">
            <th style="width:100px;">订单编号</th>
            <th style="width:200px;">地址</th>
            <th style="width:50px;">周期订单</th>
            <th style="width:100px;">支付方式</th>
            <th style="width:100px;">订单来源</th>
            <th style="width:100px;">接单时间</th>
            <th style="width:100px;">订单状态</th>
            <th style="width:100px;">接单阿姨</th>
            <th style="width:50px;">订单金额</th>
            <th style="width:100px;">操作</th>
        </tr>
        <tr style="height: 100px;">
            <td><?= Html::encode($model->order_code) ?></td>
            <td><?= HtmlPurifier::process($model->order_address) ?></td>
            <td><?= $model->order_is_parent==1?'周期':'单次'; ?></td>
            <td><?= Html::encode($model->orderExtPay->orderPayTypeName); ?></td>
            <td><?= $order_worker_assign_type[$model->orderExtWorker->order_worker_assign_type]; ?></td>
            <td><?= !empty($model->orderExtWorker->order_worker_assign_time)?date('Y-m-d',$model->orderExtWorker->order_worker_assign_time):'无'; ?></td>
            <td><?= $model->orderExtStatus->order_status_boss; ?></td>
            <td><?= $model->orderExtWorker->order_worker_name; ?></td>
            <td><?= $model->order_money; ?></td>
            <td><p><a href="/order/order/edit?id=<?= Html::encode($model->order_code) ?>">查看评论</a></p>
            <p><a href="/order/order/edit?id=<?= Html::encode($model->order_code) ?>">查看投诉</a></p></td>
        </tr>
    </table>
</div>