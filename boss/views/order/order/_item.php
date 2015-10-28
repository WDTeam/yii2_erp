<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>

 <div class="m_tab">
   <div class="m_cek"><input type="checkbox" /></div>
   <table cellspacing="0" cellpadding="0" border="1">
			<tr class="first">
            	<th style="width: 28%;">订单编号：<?= Html::encode($model->order_code) ?><span>服务类型</span></th>
                <th>支付状态</th>
                <th></th>  
                <th></th>
                <th class="m_colo"><?= Html::encode($model->orderExtStatus->order_status_name) ?></th>
            </tr>
            <tr>
            	<input type="hidden" class="order_id" value="<?= Html::encode($model->id) ?>" />
            	<input type="hidden" class="customer_phone" value="<?= Html::encode($model->orderExtCustomer->order_customer_phone) ?>" />
            	<td><?= empty($model->orderExtCustomer->order_customer_phone) ? '用户手机' : Html::encode($model->orderExtCustomer->order_customer_phone) ?>　用户身份<br />
            	    <?= Html::encode($model->order_src_name) ?>下单<br />
            	    2015-09-18   9:00-11:00<br />
            	    <?= HtmlPurifier::process($model->order_address) ?>
            	</td>
                <td>总金额：<?= Html::encode($model->order_money) ?>元<br />
            	           优惠券：10元<br />
            	           需支付：40元<br />
            	          支付方式：线上付款
            	</td>
            	<td>指定阿姨：李艳芬<br />
            	    13478906879<br />
            	           全职全日<br />
            	          北京大悦城门店
            	</td>
            	<td>2015-09-19 20:00 下单
            	</td>
            	<td>
            		<p><a href="###">查看订单</a></p>
            		<p><a href="###" class="m_tousu">投诉</a></p>
            		<!-- <p><a href="###">发送短信</a></p> -->
            		<p><a href="###" class="m_quxiao">取消订单</a></p>
            	</td>
            </tr>
		</table>
   </div>