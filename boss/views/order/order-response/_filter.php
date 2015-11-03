<?php

use yii\helpers\Url;
//use boss\models\order\Order;
use core\models\order\OrderSearch;
?>

<div class="heading heading_top">
	<h3 class="panel-title">筛选</h3>
</div>

<div class="m_from">
	<ul class="lis" id="list">
		<p>订单状态：</p>
		<?php 
		  echo $this->render('_filteritem', ['filter_name' => 'order_status_dict_id', 'items' => $statusList]);
		?>						    		
	</ul>
	<div class="clear"></div>
</div>

<!---------------------订单状态开始-------------------->
<!--
<div class="heading heading_top">
	<h3 class="panel-title">订单状态</h3>
</div>
-->
