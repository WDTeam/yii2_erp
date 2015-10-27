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
<style>
	.mar-t {margin-top:20px;}
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
						 	客户手机<input type="text" name="order_customer_phone" value="<?php if(!empty($params['order_customer_phone'])){ echo $params['order_customer_phone']; }?>"/>
						 	</label>
						 	<label class="m_iphone">
						 	阿姨手机<input type="text" name="order_worker_phone" value="<?php if(!empty($params['order_worker_phone'])){ echo $params['order_worker_phone']; }?>"/>
						 	</label><label class="m_iphone">
						 	订单编号<input type="text" name="order_id" value="<?php if(!empty($params['order_id'])){echo $params['order_id'];}?>"/>
						 	</label><label class="m_iphone">
						 	投诉编号<input type="text" name="id" value="<?php if(!empty($params['id'])){ echo $params['id'];}?>"/>
						 	</label>
						 </div>
						<div class="m_fr mar-t">
							<label class="m_iphone">
						  		阿姨姓名
						  	</label><input type="text" name="order_worker_name" value=""/>
						</div>
						 <div class="m_fr mar-t">
                            <label class="m_ipone">下单时间:</label>
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
						    		<li <?php if(empty($params['complaint_type'])){?> class="cur" <?php }?>>全部</li>
						    		<?php if(!empty($comType)){
						    		foreach ($comType as $keyt=>$valt){?>					    		
						    		<li <?php if(!empty($params['complaint_type']) && $keyt == $params['complaint_type']){?>class="cur"<?php }?>><a href="<?php echo "/order/order-complaint/index?complaint_type={$keyt}";?>"><?php echo $valt;?></a></li>
						    		<?php }} ?>
						    	</ul>
						    	
						    	<ul class="lis" id="list">
						    		<p>订单状态：</p>
						    		<li <?php if(empty($params['complaint_status'])){?> class="cur" <?php }?>>全部</li>
						    		<?php if(!empty($comStatus)){
						    			foreach ($comStatus as $key=>$val){?>
						    		<li <?php if(!empty($params['complaint_status']) && $key == $params['complaint_status']){?>class="cur"<?php }?>><a href="<?php echo "/order/order-complaint/index?complaint_status={$key}";?>"><?php echo $val;?></a></li>
						    		<?php }}?>
						    	</ul>
						    	
						    	<ul class="lis" id="list">
						    		<p>投诉渠道：</p>
						    		<li class="cur">全部</li>
						    		<li><a href="">App</a></li>
						    		<li><a href="">第三方</a></li>
						    		<li><a href="">后台</a></li>
						    	</ul>
						    	<ul class="lis" id="list">
						    		<p>投诉级别：</p>
						    		<li <?php if(!isset($params['complaint_level'])){?> class="cur" <?php }?>>全部</li>
						    		<?php if(!empty($comLevel)){
						    			foreach ($comLevel as $keyl=>$vall){?>
						    		<li <?php if(!empty($params['complaint_level']) && $vall == $params['complaint_level']){?>class="cur"<?php }?>><a href="<?php echo "/order/order-complaint/index?complaint_level={$vall}";?>"><?php echo $vall;?></a></li>
						    		<?php }}?>
						    	</ul>
						    	<ul class="lis" id="list">
						    		<p>投诉部门：</p>
						    		<li <?php if(empty($params['complaint_section'])){?> class="cur" <?php }?>>全部</li>
						    		<?php if(!empty($devpart)){
						    		foreach ($devpart as $keyrt=>$valrt){?>
						    		<li <?php if(!empty($params['complaint_section']) && $keyrt == $params['complaint_section']){?> class="cur" <?php }?>><a href="<?php echo "/order/order-complaint/index?complaint_section={$keyrt}"?>"><?php echo $valrt;?></a></li>
									<?php }}?>
						    	</ul>
						    	<div class="clear"></div>
						    </div>
						
						<!---------------------订单状态开始-------------------->
								<div class="m_from">	     
				    <div class="clear"></div>
				    </div>
  	 	 	       <div class="heading heading_top">
						<h3 class="panel-title">当前刷选条件</h3>
				   </div>
	<?php 
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item'
    ]);    
    ?>
						 
						 
						<!------------------翻页开始------------------>
						    <div class="com_pages_list">
					              <dl class="pages_list">
					                <dd><a href="###">«</a></dd>
					                <dd class="on"><a href="###">1</a></dd>
					                <dd><a href="###">2</a></dd>
					                <dd><a href="###">3</a></dd>
					                <dd><a href="###">4</a></dd>
					                <dd><a href="###">5</a></dd>
					                <dd><a href="###">6</a></dd>
					                <dd><a href="###">7</a></dd>
					                <dd><a href="###">»</a></dd>
					              </dl>
					         </div>

						
						
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
