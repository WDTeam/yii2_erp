<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use boss\assets\AppAsset;
use kartik\widgets\ActiveForm;
use boss\models\order\Order;
use yii\base\Object;
use core\models\order\OrderComplaint;
use yii\helpers\Url;
use boss\models\search\OrderSearch;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\order\OrderSearch $searchModel
 */
 
AppAsset::addCss($this, 'css/order_search/style.css');
AppAsset::addCss($this, 'css/order_search/dalog/animate.min.css');

AppAsset::addScript($this, 'js/order_search/script.js');
AppAsset::addScript($this, 'js/order_search/My97DatePicker/WdatePicker.js');
AppAsset::addScript($this, 'js/order_search/dalog/jquery.hDialog.min.js');	

// $this->registerCssFile('css/order_search/style.css');
// $this->registerCssFile('css/order_search/jquery-ui-1.8.17.custom.css');
// $this->registerCssFile('css/order_search/jquery-ui-timepicker-addon.css');
// $this->registerJsFile('js/order_search/jquery-2.0.3.min.js');
// $this->registerJsFile('js/order_search/script.js');
// $this->registerJsFile('js/order_search/riqi/jquery-1.7.1.min.js');
// $this->registerJsFile('js/order_search/riqi/jquery-ui-1.8.17.custom.min.js');
// $this->registerJsFile('js/order_search/riqi/jquery-ui-timepicker-addon.js');
// $this->registerJsFile('js/order_search/riqi/jquery-ui-timepicker-zh-CN.js');

$this->title = '订单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
	span {color: #000;}
	#m_warp .m_from table tr th {background: #f3f3f3;padding: 10px 0;}
</style>
     <div id="m_warp">
		  <div class="box">
		  	 <div class="conter"> 
		  	 	 <div class="m_frist">
		  	 	 	<!---------------------订单查询条件-------------------->
                    <?php            
                    echo $this->render('_search-mini-boss', ['searchModel' => $searchModel, 'searchParas' => $searchParas]);
                    ?>
					 <div class="m_from">
						 <?php
						 echo ListView::widget([
							 'dataProvider' => $dataProvider,
							 'itemView' => '_item-mini-boss',
						 ]);
						 ?>
						 <div class="clear"></div>
					 </div>
		  	 	 </div>
		  	 </div>
		  </div>
		</div>

		<!------------------弹出层开始------------------>
		
		<div id="HBox_error" class="cd-popup" role="alert">
			<div class="cd-popup-container">
				<p>提示</p>
				<ul>
					<li>请填写正确的电话号码或格式！</li>
					<li>例：19876578988！（11位数）</li>
				</ul>
				<a href="#" class="cd-popup-close img-replace"></a>
			</div> <!-- cd-popup-container -->
		</div>