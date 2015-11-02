<?php

use yii\helpers\Url;
use boss\models\order\Order;
use core\models\order\OrderSearch;
?>

<div class="heading heading_top">
	<h3 class="panel-title"><i class="glyphicon glyphicon-th-list" style="margin-right:5px;"></i>筛选</h3>
</div>

<div class="m_from">
	<ul class="lis" id="list">
		<div><p>城&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;市：</p></div>
		<div>
		<?php
		  echo $this->render('_filteritem', ['filter_name' => 'city_id', 'items' => Order::getOnlineCityList(null)]);
        ?>
		</div>
		
	</ul>
	<ul class="lis" id="list">
		<div>
			<p>服务类型：</p>
		</div>
		<div style="margin-left:80px;">
		<?php
		  echo $this->render('_filteritem', ['filter_name' => 'order_service_type_id', 'items' => Order::getServiceItems()]);
		?>	
		</div>
		
	</ul>						    	
	<ul class="lis" id="list">
		<div>
			<p>订单状态：</p>
		</div>
		<div style="margin-left:80px;">
		<?php 
		  echo $this->render('_filteritem', ['filter_name' => 'order_status_dict_id', 'items' => Order::getStatusList()]);
		?>	
		</div>
							    		
	</ul>
	
	<ul class="lis" id="list">
		<div>
			<p>下单渠道：</p>
		</div>
		<div style="margin-left:80px;">
		<?php 
		  echo $this->render('_filteritem', ['filter_name' => 'channel_id', 'items' => $searchModel->getOrderChannelList()]);
		?>
		</div>
		
	</ul>
	<div class="clear"></div>
</div>

<!---------------------订单状态开始-------------------->
<div class="heading heading_top">
	<h3 class="panel-title">订单状态</h3>
</div>