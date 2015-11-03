<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use dbbase\models\order\OrderStatusDict;
use boss\models\worker\Worker;
use boss\models\order\Order;
?>

 <div class="m_tab">
   <div class="m_cek"><input type="checkbox" /></div>
   <table cellspacing="0" cellpadding="0" border="1">
			<tr class="first">
            	<th style="width: 28%;">订单编号：<?= Html::encode($model->order_code) ?><span><?= Html::encode($model->order_service_type_name) ?></span></th>
                <th><?= $model->orderExtStatus->order_status_dict_id > OrderStatusDict::ORDER_INIT ? '已支付' : '未支付' ?></th>
                <th></th>  
                <th></th>
                <th class="m_colo"><?= Html::encode($model->orderExtStatus->order_status_name) ?></th>
            </tr>
            <tr>
            	<input type="hidden" class="order_id" value="<?= Html::encode($model->id) ?>" />
            	<input type="hidden" class="customer_phone" value="<?= Html::encode($model->orderExtCustomer->order_customer_phone) ?>" />
            	<td>用户电话：<?= Html::encode($model->orderExtCustomer->order_customer_phone) ?><br />
            	         用户身份：<?= $model->orderExtCustomer->order_customer_is_vip == 1 ? '会员' : '非会员' ?><br />
            	         下单渠道：<?= Html::encode($model->order_channel_name) ?><br />
            	         服务时间：<?= $model->getOrderBookedDate().' '.$model->getOrderBookedTimeArrange() ?><br />
            	         客户地址：<?= HtmlPurifier::process($model->order_address) ?>
            	</td>
                <td>总金额：<?= Html::encode($model->order_money) ?>元<br />
            	           优惠券：<?= Html::encode($model->orderExtPay->order_use_coupon_money) ?>元<br />
            	           需支付：<?= Html::encode($model->orderExtPay->order_pay_money) ?>元<br />
            	          支付方式：<?= Html::encode($model->orderExtPay->order_pay_channel_name) ?>
            	</td>
            	<td>
            	   <?php 
            	   if ($model->orderExtWorker->worker_id > 0)
            	   {
                       echo '接单阿姨：'.$model->orderExtWorker->order_worker_name;
                       echo '<br />';
                       echo '阿姨手机：'.$model->orderExtWorker->order_worker_phone.'<br />';
                       echo '阿姨身份：'.$model->orderExtWorker->order_worker_type_name.'<br />';
                       echo '所属门店：'.$model->orderExtWorker->order_worker_shop_name;
            	   }
            	   else 
            	   {
            	       if (!empty($model->order_booked_worker_id))
            	       {
            	           $worker = Worker::findOne($model->order_booked_worker_id);
            	           
            	           echo '指定阿姨：'.$worker->worker_name;
            	           echo '<br />';
            	           echo '阿姨手机：'.$worker->worker_phone.'<br />';
            	           echo '阿姨身份：'.Worker::getWorkerTypeShow($worker->worker_type).'<br />';
            	           echo '所属门店：'.Worker::getShopName($worker->shop_id);            	           
            	       }
            	   }
            	   ?>
            	</td>
            	<td><?= date('Y-m-d H:i', $model->created_at) ?> 下单
            	</td>
            	<td>
            		<p><a href="/order/order/edit?id=<?= Html::encode($model->id) ?>">查看订单</a></p>
            		
            		<?php if($model->orderExtStatus->order_status_dict_id != OrderStatusDict::ORDER_INIT):?>
            		<p><a href="###" class="m_tousu">投诉</a></p>
            		<?php endif;?>
            		<!-- <p><a href="###">发送短信</a></p> -->
            		
            		<?php if($model->orderExtStatus->order_status_dict_id != OrderStatusDict::ORDER_CANCEL):?>
            		<p><a href="###" class="m_quxiao">取消订单</a></p>
            		<?php endif;?>
            	</td>
            </tr>
		</table>
   </div>