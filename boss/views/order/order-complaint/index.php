<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use boss\assets\AppAsset;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\order\OrderSearch $searchModel
 */
 
AppAsset::addCss($this, 'css/order_search/style.css');
AppAsset::addCss($this, 'css/order_search/jquery-ui-1.8.17.custom.css');
AppAsset::addCss($this, 'css/order_search/jquery-ui-timepicker-addon.css');
AppAsset::addScript($this, 'js/order_search/jquery-2.0.3.min.js');
AppAsset::addScript($this, 'js/order_search/script.js');
AppAsset::addScript($this, 'js/order_search/riqi/jquery-1.7.1.min.js');
AppAsset::addScript($this, 'js/order_search/riqi/jquery-ui-1.8.17.custom.min.js');
AppAsset::addScript($this, 'js/order_search/riqi/jquery-ui-timepicker-addon.js');
AppAsset::addScript($this, 'js/order_search/riqi/jquery-ui-timepicker-zh-CN.js');

// $this->registerCssFile('css/order_search/style.css');
// $this->registerCssFile('css/order_search/jquery-ui-1.8.17.custom.css');
// $this->registerCssFile('css/order_search/jquery-ui-timepicker-addon.css');
// $this->registerJsFile('js/order_search/jquery-2.0.3.min.js');
// $this->registerJsFile('js/order_search/script.js');
// $this->registerJsFile('js/order_search/riqi/jquery-1.7.1.min.js');
// $this->registerJsFile('js/order_search/riqi/jquery-ui-1.8.17.custom.min.js');
// $this->registerJsFile('js/order_search/riqi/jquery-ui-timepicker-addon.js');
// $this->registerJsFile('js/order_search/riqi/jquery-ui-timepicker-zh-CN.js');

$this->title = '订单投诉管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- 新增样式表 -->
<style>
	.mar-t {margin-top: 15px;}
	#m_warp .m_riqi .btn {margin: 0px 80px;width: 160px;height: 33px; background-color: #f6a202 !important;color: #fff !important;}
</style>
     <div id="m_warp">
		  <div class="box">
		  	 <div class="conter"> 
		  	 	 <div class="m_frist">
		  	 	 	<!---------------------查询开始-------------------->
		  	 	 	<div class="heading">
						<h3 class="panel-title">查询</h3>
					</div>
					
					<div class="m_from">
                    <?php $form = ActiveForm::begin([
                        //'type' => ActiveForm::TYPE_VERTICAL,
                        'action' => ['order/order-complaint/index'],
                        'method' => 'get',
                    ]); ?>						
						<?php //echo $form->field($searchModel, 'order_customer_phone')->TextInput(['class' => 'm_ipu'])->label('用户电话 :', ['class' => 'm_ipone']); ?>
						<?php //echo $form->field($searchModel, 'worker_id')->TextInput(['class' => 'm_ipu'])->label('阿姨电话 :', ['class' => 'm_ipone']); ?>
						<?php //echo $form->field($searchModel, 'order_code')->TextInput(['class' => 'm_ipu'])->label('订单编号 :', ['class' => 'm_ipone']); ?>
						
						<div class="m_riqi">
						 <div class="m_fr">
						 	<label class="m_iphone">
						 	客户手机<input type="text" name="OrderComplaintSearch[complaint_phone]" value="<?php if(!empty($params['OrderComplaintSearch']['complaint_phone'])){ echo $params['OrderComplaintSearch']['complaint_phone']; }?>"/>
						 	</label>
						 	<label class="m_iphone">
						 	阿姨手机<input type="text" name="OrderComplaint[order_worker_phone]" value="<?php if(!empty($params['OrderComplaintSearch']['order_worker_phone'])){ echo $params['OrderComplaintSearch']['order_worker_phone']; }?>"/>
						 	</label><label class="m_iphone">
						 	订单编号<input type="text" name="OrderComplaintSearch[order_id]" value="<?php if(!empty($params['OrderComplaintSearch']['order_id'])){echo $params['OrderComplaintSearch']['order_id'];}?>"/>
						 	</label><label class="m_iphone">
						 	投诉编号<input type="text" name="OrderComplaintSearch[id]" value="<?php if(!empty($params['OrderComplaintSearch']['id'])){ echo $params['OrderComplaintSearch']['id'];}?>"/>
						 	</label>
						 </div>
						<div class="m_fr mar-t">
							<label class="m_iphone">
						  		阿姨姓名
						  	</label><input type="text" name="OrderComplaintSearch[order_worker_name]" value="<?php if(!empty($params['OrderComplaintSearch']['order_worker_name'])){ echo $params['OrderComplaintSearch']['order_worker_name'];}?>"/>
						</div>
						 <div class="m_fr mar-t">
                            <label class="m_ipone">创建时间:</label>
							<input type="text" name="starttime" class="ui_timepicker" value="" placeholder=""> 到
							<input type="text" name="endtime" class="ui_timepicker" value="" placeholder="">
						   </div>
						    <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
						</div>
					<?php ActiveForm::end(); ?>
					  <div class="clear"></div>
					</div>
						<!---------------------查询开始-------------------->
						
				  	 	 	<div class="heading heading_top">
								<h3 class="panel-title">筛选</h3>
							</div>
						    
						    <div class="m_from">
						    	<ul class="lis" id="list">
						    		<p>投诉类型：</p>
						    		<li <?php if(empty($params['OrderComplaintSearch']['complaint_type'])){?> class="cur" <?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_type]=0";?>">全部</a></li>
						    		<?php if(!empty($comType)){
						    		foreach ($comType as $keyt=>$valt){?>					    		
						    		<li <?php if(!empty($params['OrderComplaintSearch']['complaint_type']) && $keyt == $params['OrderComplaintSearch']['complaint_type']){?>class="cur"<?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_type]={$keyt}";?>"><?php echo $valt;?></a></li>
						    		<?php }} ?>
						    	</ul>
						    	
						    	<ul class="lis" id="list">
						    		<p>订单状态：</p>
						    		<li <?php if(empty($params['OrderComplaintSearch']['complaint_status'])){?> class="cur" <?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_status]=0";?>">全部</a></li>
						    		<?php if(!empty($comStatus)){
						    			foreach ($comStatus as $key=>$val){?>
						    		<li <?php if(!empty($params['OrderComplaintSearch']['complaint_status']) && $key == $params['OrderComplaintSearch']['complaint_status']){?>class="cur"<?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_status]={$key}";?>"><?php echo $val;?></a></li>
						    		<?php }}?>
						    	</ul>
						    	
						    	<ul class="lis" id="list">
						    		<p>投诉渠道：</p>
						    		<li <?php if(empty($params['OrderComplaintSearch']['complaint_channel'])){?> class="cur" <?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_channel]=0"?>">全部</a></li>
						    		<?php if(!empty($channel)){
						    		foreach ($channel as $keynl=>$valnl){?>
						    		<li <?php if(!empty($params['OrderComplaintSearch']['complaint_channel']) && $keynl == $params['OrderComplaintSearch']['complaint_channel']){?> class="cur" <?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_channel]={$keynl}"?>"><?php echo $valnl;?></a></li>
									<?php }}?>
						    	</ul>
						    	<ul class="lis" id="list">
						    		<p>投诉级别：</p>
						    		<li <?php if(!isset($params['OrderComplaintSearch']['complaint_level'])){?> class="cur" <?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_level]=0";?>">全部</a></li>
						    		<?php if(!empty($comLevel)){
						    			foreach ($comLevel as $keyl=>$vall){?>
						    		<li <?php if(!empty($params['OrderComplaintSearch']['complaint_level']) && $vall == $params['OrderComplaintSearch']['complaint_level']){?>class="cur"<?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_level]={$vall}";?>"><?php echo $vall;?></a></li>
						    		<?php }}?>
						    	</ul>
						    	<ul class="lis" id="list">
						    		<p>投诉部门：</p>
						    		<li <?php if(empty($params['OrderComplaintSearch']['complaint_section'])){?> class="cur" <?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_section]=0"?>">全部</a></li>
						    		<?php if(!empty($devpart)){
						    		foreach ($devpart as $keyrt=>$valrt){?>
						    		<li <?php if(!empty($params['OrderComplaintSearch']['complaint_section']) && $keyrt == $params['OrderComplaintSearch']['complaint_section']){?> class="cur" <?php }?>><a href="<?php echo "/order/order-complaint/index?OrderComplaintSearch[complaint_section]={$keyrt}"?>"><?php echo $valrt;?></a></li>
									<?php }}?>
						    	</ul>
						    	<div class="clear"></div>
						    </div>
						
						<!---------------------订单状态开始-------------------->
								
	<?php 
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item'
    ]);    
    ?>
						 						 																
						<!------------------弹出层开始------------------>
						
						<div class="cd-popup" role="alert">
							<div class="cd-popup-container">
								<p>提示</p>
								<ul>
									<li>请填写正确的电话号码或格式！</li>
									<li>例：19876578988！（11位数）</li>
								</ul>
								<a href="#" class="cd-popup-close img-replace"></a>
							</div> <!-- cd-popup-container -->
						</div>
						
						
						
						
					
		  	 	 </div>
		  	 </div>
		  </div>
		</div>
<?php 
$this->registerJs('
	    $(function () {
	        $(".ui_timepicker").datetimepicker({
	            //showOn: "button",
	            //buttonImage: "./css/images/icon_calendar.gif",
	            //buttonImageOnly: true,
	            showSecond: true,
	            timeFormat: "hh:mm:ss",
	            stepHour: 1,
	            stepMinute: 1,
	            stepSecond: 1
	        })
        	$("#list li").click(
		       	function(){
					$(this).addClass("cur");
					$(this).siblings("li").removeClass("cur");
			   }
		     );
        })
    ');

?>
