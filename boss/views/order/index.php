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

$this->title = '订单管理';
$this->params['breadcrumbs'][] = $this->title;
?>

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
                        'action' => ['order/index'],
                        'method' => 'get',
                    ]); ?>						
						<?php echo $form->field($searchModel, 'order_customer_phone')->TextInput(['class' => 'm_ipu'])->label('用户电话', ['class' => 'm_ipone']); ?>
						<?php echo $form->field($searchModel, 'order_customer_phone')->TextInput(['class' => 'm_ipu'])->label('阿姨电话', ['class' => 'm_ipone']); ?>
						<?php echo $form->field($searchModel, 'order_code')->TextInput(['class' => 'm_ipu'])->label('订单编号', ['class' => 'm_ipone']); ?>
						
						<div class="m_riqi">
							<label class="m_ipone">下单时间:</label>
							<input type="text" name="datetime" class="ui_timepicker" value="" placeholder="日期时间"> 到
							<input type="text" name="datetime" class="ui_timepicker" value="" placeholder="日期时间">

							<label class="m_ipone m_iponeleft">服务时间:</label>
							<input type="text" name="datetime" class="ui_timepicker" value="" placeholder="日期时间"> 到
							<input type="text" name="datetime" class="ui_timepicker" value="" placeholder="日期时间">
						    <p class="cd-popup-trigger"><a href="javascript:;">搜索</a></p>
						</div>
					<?php ActiveForm::end(); ?>
					</div>
						
						<!---------------------查询开始-------------------->
				  	 	 	<div class="heading heading_top">
								<h3 class="panel-title">筛选</h3>
							</div>
						    
						    <div class="m_from">
						    	<ul class="lis" id="list">
						    		<p>服务类型：</p>
						    		<li class="cur">全部</li>
						    		<li>专业保洁</li>
						    		<li>家电清洗</li>
						    		<li>家居养护</li>
						    	</ul>
						    	
						    	<ul class="lis" id="list">
						    		<p>订单状态：</p>
						    		<li class="cur">全部</li>
						    		<li>待付款</li>
						    		<li>待指派</li>
						    		<li>待服务</li>
						    		<li>已完成</li>
						    		<li>已取消</li>
						    		<li>投诉订单</li>
						    	</ul>
						    	
						    	<ul class="lis" id="list">
						    		<p>下单渠道：</p>
						    		<li class="cur">全部</li>
						    		<li>App</li>
						    		<li>第三方</li>
						    		<li>Api</li>
						    	</ul>
						    	<div class="clear"></div>
						    </div>
						
						<!---------------------订单状态开始-------------------->
		  	 	 	       <div class="heading heading_top">
								<h3 class="panel-title">订单状态</h3>
						   </div>
						
						    <div class="m_from">
						    	<ul class="lis liss" id="list">
						    		<li>按下单时间 ↑</li>
						    		<li>按服务时间 ↑</li>
						    	</ul>
						    	<h6><input type="checkbox" /><a href="javascript">可开发票</a></h6>
						    	<p class="m_daoc"><a href="javascript:;">Excel导出</a></p>
						    	<div class="clear"></div>
						     </div>
						    
							 <div class="m_from">
						    	<div class="m_shuji">
								  <h3 class="panel-title">共查询出 <span>24445</span> 条记录</h3>
						       </div>
						     
						     <div class="m_tab">
						       <div class="m_cek"><input type="checkbox" /></div>
						       <table cellspacing="0" cellpadding="0" border="1">
										<tr class="first">
					                    	<th style="width: 28%;">订单编号：17135929<span>服务类型</span></th>
					                        <th>支付状态</th>
					                        <th></th>  
					                        <th></th>
					                        <th class="m_colo">订单状态</th>
					                    </tr>
					                    <tr>
					                    	<td>18612345678<br />
					                    	    App下单<br />
					                    	    2015-09-18   9:00-11:00<br />
					                    	          北京，中国水科院南小区，9号楼130
					                    	</td>
					                        <td>18612345678<br />
					                    	    App下单<br />
					                    	    2015-09-18   9:00-11:00<br />
					                    	          北京，中国水科院南小区，9号楼130
					                    	</td>
					                    	<td>18612345678<br />
					                    	    App下单<br />
					                    	    2015-09-18   9:00-11:00<br />
					                    	          北京，中国水科院南小区，9号楼130
					                    	</td>
					                    	<td>18612345678<br />
					                    	    App下单<br />
					                    	    2015-09-18   9:00-11:00<br />
					                    	          北京，中国水科院南小区，9号楼130
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
		  	 
						    	<div class="clear"></div>
						    </div>
						    
						    <div class="heading heading_top">
								<h3 class="panel-title">周期订单展示</h3>
						   </div>
						
						   	 <div class="m_from">
						    	<div class="m_nav">
						    		<p>订单编号：2345  （周期订单） <b>家庭保洁</b> <span><a href="###">查看详情</a></span></p>
						    	</div>
						    
						     <div class="m_tab">
						       <div class="m_cek"><input type="checkbox" /></div>
						       <table cellspacing="0" cellpadding="0" border="1">
										<tr class="first">
					                    	<th style="width: 28%;">订单编号：17135929<span>服务类型</span></th>
					                        <th>支付状态</th>
					                        <th></th>  
					                        <th></th>
					                        <th class="m_colo">订单状态</th>
					                    </tr>
					                    <tr>
					                    	<td>18612345678<br />
					                    	    App下单<br />
					                    	    2015-09-18   9:00-11:00<br />
					                    	          北京，中国水科院南小区，9号楼130
					                    	</td>
					                        <td>18612345678<br />
					                    	    App下单<br />
					                    	    2015-09-18   9:00-11:00<br />
					                    	          北京，中国水科院南小区，9号楼130
					                    	</td>
					                    	<td>18612345678<br />
					                    	    App下单<br />
					                    	    2015-09-18   9:00-11:00<br />
					                    	          北京，中国水科院南小区，9号楼130
					                    	</td>
					                    	<td>18612345678<br />
					                    	    App下单<br />
					                    	    2015-09-18   9:00-11:00<br />
					                    	          北京，中国水科院南小区，9号楼130
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
		  	                   <div class="m_quan">
						             <div class="m_cek"><input type="checkbox" /><a href="javascript:;">全选</a></div>
		  	                         <ul class="lis liss" id="list">
						    		      <li>申请发票</li>
						    		      <li>取消订单 </li>
						    	     </ul>
		  	                   </div>
		  	                  
						    	<div class="clear"></div>
						    </div>
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
