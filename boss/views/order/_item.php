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
                <th class="m_colo">订单状态</th>
            </tr>
            <tr>
            	<td><?= empty($model->orderExtCustomer->order_customer_phone) ? '用户电话' : Html::encode($model->orderExtCustomer->order_customer_phone) ?>　用户身份<br />
            	    <?= Html::encode($model->order_src_name) ?>下单<br />
            	    2015-09-18   9:00-11:00<br />
            	    <?= HtmlPurifier::process($model->order_address) ?>
            	</td>
                <td>总金额：50元<br />
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
            		<p><a href="###">投诉</a></p>
            		<p><a href="###">发送短信</a></p>
            		<p><a href="###">取消订单</a></p>
            	</td>
            </tr>
		</table>
   </div>