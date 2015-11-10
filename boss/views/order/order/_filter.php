<?php

use yii\helpers\Url;
use boss\models\order\Order;
use core\models\order\OrderSearch;
use boss\models\operation\OperationCategory;
?>

<div class="heading">
	<h3 class="panel-title"><i class="glyphicon glyphicon-th-list" style="margin-right:5px;"></i>筛选</h3>
</div>

<div class="m_from">
	<ul class="lis" >
		<div><p>城&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;市：</p></div>
		<div>
		<?php
		  echo $this->render('_filteritem', ['filter_name' => 'cities', 'items' => Order::getOnlineCityList()]);
        ?>
		</div>
		
	</ul>
	<ul class="lis" >
		<div>
			<p>服务类型：</p>
		</div>
		<div style="margin-left:80px;">
		<?php
		  echo $this->render('_filteritem', ['filter_name' => 'types', 'items' => Order::getServiceTypes()]);
		?>	
		</div>
		
	</ul>						    	
	<ul class="lis" >
		<div>
			<p>订单状态：</p>
		</div>
		<div style="margin-left:80px;">
		<?php 
		  echo $this->render('_filteritem', ['filter_name' => 'statuss', 'items' => Order::getStatusList()]);
		?>	
		</div>
							    		
	</ul>
	
	<ul class="lis" >
		<div>
			<p>下单渠道：</p>
		</div>
		<div style="margin-left:80px;">
		<?php 
		  echo $this->render('_filteritem', ['filter_name' => 'channels', 'items' => $searchModel->getOrderChannelList()]);
		?>
		</div>
		
	</ul>
	<div class="clear"></div>
</div>

<!---------------------订单状态开始-------------------->
<div class="heading heading_top">
	<h3 class="panel-title">订单信息</h3>
</div>