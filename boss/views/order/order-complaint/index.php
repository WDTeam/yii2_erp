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
							 	<label class="m_iphone">用户电话：</label>
							 	<input type="text" name="complaint_phone" value=""/>
							 	<label class="m_iphone">阿姨电话：</label>
							 	<input type="text" name="worker_phone" value=""/>
							 	<label class="m_iphone">阿姨姓名：</label>
								<input type="text" name="worker_name" value=""/>
							</div>
							<div class="m_fr mar-t">
								<label class="m_iphone">投诉编号：</label>
							 	<input type="text" name="id" value=""/>
							 	<label class="m_iphone">订单编号：</label>
							 	<input type="text" name="order_id" value=""/>
							 	<?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
							</div>
							<div class="m_fr mar-t">
	                            <label class="m_ipone">下单时间：</label>
								<input type="text" name="starttime" class="ui_timepicker" value="" placeholder=""> 到
								<input type="text" name="endtime" class="ui_timepicker" value="" placeholder="">
						    </div>
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
						    		<li class="cur">全部</li>
						    		<?php if(!empty($comType)){
						    		foreach ($comType as $keyt=>$valt){?>					    		
						    		<li <?php if(!empty($params['complaint_type']) && $keyt == $params['complaint_type']){?>class="cur"<?php }?>><a href="<?php echo $url."&complaint_type={$keyt}";?>"><?php echo $valt;?></a></li>
						    		<?php }} ?>
						    	</ul>
						    	
						    	<ul class="lis" id="list">
						    		<p>订单状态：</p>
						    		<li <?php if(!isset($params['complaint_status'])){?> class="cur" ><?php }?>全部</li>
						    		<?php if(!empty($comStatus)){
						    			foreach ($comStatus as $key=>$val){?>
						    		<li <?php if(!empty($params['complaint_status']) && $key == $params['complaint_status']){?>class="cur"<?php }?>><a href="<?php echo "index.php?complaint_status={$key}";?>"><?php echo $val;?></a></li>
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
						    		<li <?php if(!isset($params['complaint_level'])){?> class="cur" ><?php }?>全部</li>
						    		<?php if(!empty($comLevel)){
						    			foreach ($comLevel as $keyl=>$vall){?>
						    		<li <?php if(!empty($params['complaint_level']) && $vall == $params['complaint_level']){?>class="cur"<?php }?>><a href="<?php echo "index.php?complaint_level={$vall}";?>"><?php echo $vall;?></a></li>
						    		<?php }}?>
						    	</ul>
						    	<ul class="lis" id="list">
						    		<p>投诉部门：</p>
						    		<li class="cur">全部</li>
						    		<li><a href="">线下运营</a></li>
						    		<li>客服</li>
						    		<li>线下推广</li>
						    		<li>财务</li>
						    		<li>技术</li>
						    		<li>公司</li>
						    	</ul>
						    	<div class="clear"></div>
						    </div>
						
						<!---------------------订单状态开始-------------------->
											 <div class="m_from">	     
    <?php 
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
    ]);    
    ?>   
						    	<div class="clear"></div>
						    </div>
		  	 	 	       <div class="heading heading_top">
								<h3 class="panel-title">当前刷选条件</h3>
						   </div>
						   
						
						   	 <div class="m_from">
						     <div class="m_tab">
						       <div class="m_cek"><input type="checkbox" /></div>
						       <table cellspacing="0" cellpadding="0" border="1">
										<tr class="first">
					                    	<th style="width: 28%;">投&nbsp;&nbsp;诉ID：17135929</th>
					                        <th>投诉阿姨</th>
					                        <th>投诉详情</th>  
					                        <th>处理详情</th>
					                        <th class="m_colo">订单状态</th>
					                    </tr>
					                    <tr>
					                    	<td>订   单ID：99082357<br/>
												客户手机：18765656565<br/>
												投诉来源：App<br/>
												投诉部门：线下运营部<br/>
												投诉级别：A<br/>
												投诉类型：磨洋工<br/>
					                    	</td>
					                        <td>阿姨姓名：高姗<br/>
												阿姨编号：9087<br/>
												阿姨身份：全职全日<br/>
												阿姨手机：18765656565<br/>
												所属门店：北京大悦城店<br/>
					                    	</td>
					                    	<td>
					                    		客户来电表示此订单实际服务两个小时，客户使用一张25元优惠券，实际应收客户25元，但是阿姨表示不知道此优惠，收取客户50元现金。现客户要求反馈此问题，请相关部门协助核实并处理、谢谢。
					                    	</td>
					                    	<td>已向客户致歉，客户表示下次还要此阿姨服务，下次少收25元就行了
					                    	</td>
					                    	<td>
					                    		<p><a href="###">处理投诉</a></p>
					                    		<p><a href="###">查看关联投诉</a></p>
					                    	</td>
					                    </tr>
									</table>
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
