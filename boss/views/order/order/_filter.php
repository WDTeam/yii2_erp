<?php

use yii\helpers\Url;
use boss\models\order\Order;
use core\models\order\OrderSearch;
?>

<div class="heading heading_top">
	<h3 class="panel-title">筛选</h3>
</div>

<div class="m_from">
	<ul class="lis" id="list">
		<p>城市：</p>
		<?php
		  echo $this->render('_filteritem', ['filter_name' => 'city_id', 'items' => Order::getOnlineCityList()]);
        ?>
	</ul>
	<ul class="lis" id="list">
		<p>服务类型：</p>
		<?php
		  echo $this->render('_filteritem', ['filter_name' => 'order_service_type_id', 'items' => Order::getServiceItems()]);
		?>	
	</ul>						    	
	<ul class="lis" id="list">
		<p>订单状态：</p>
		<?php 
		  echo $this->render('_filteritem', ['filter_name' => 'order_status_dict_id', 'items' => Order::getStatusList()]);
		?>						    		
	</ul>
	
	<ul class="lis" id="list">
		<p>下单渠道：</p>
		<?php 
		  echo $this->render('_filteritem', ['filter_name' => 'channel_id', 'items' => $searchModel->getOrderChannelList()]);
		?>
	</ul>
	<div class="clear"></div>
</div>

<!---------------------订单状态开始-------------------->
<div class="heading heading_top">
	<h3 class="panel-title">订单状态</h3>
</div>