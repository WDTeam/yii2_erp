<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use boss\assets\AppAsset;
use kartik\widgets\ActiveForm;
use boss\models\order\Order;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\order\OrderSearch $searchModel
 */
 
AppAsset::addCss($this, 'css/order_search/style.css');
AppAsset::addCss($this, 'css/order_search/jquery-ui-1.8.17.custom.css');
AppAsset::addCss($this, 'css/order_search/jquery-ui-timepicker-addon.css');
AppAsset::addCss($this, 'css/order_search/dalog/animate.min.css');
AppAsset::addScript($this, 'js/order_search/jquery-2.0.3.min.js');
AppAsset::addScript($this, 'js/order_search/script.js');
AppAsset::addScript($this, 'js/order_search/riqi/jquery-1.7.1.min.js');
AppAsset::addScript($this, 'js/order_search/riqi/jquery-ui-1.8.17.custom.min.js');
AppAsset::addScript($this, 'js/order_search/riqi/jquery-ui-timepicker-addon.js');
AppAsset::addScript($this, 'js/order_search/riqi/jquery-ui-timepicker-zh-CN.js');
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
		  	 	 	<!---------------------查询开始-------------------->
                    <?php            
                    echo $this->render('_search', ['searchModel' => $searchModel]);
                    ?>
						
						<!---------------------查询开始-------------------->
				  	 	 	<div class="heading heading_top">
								<h3 class="panel-title">筛选</h3>
							</div>
						    
						    <div class="m_from">
						    	<ul class="lis" id="list">
						    		<p>城市：</p>
						    		<li class="cur">全部</li>
						    		<?php 
						    		foreach (Order::getOnlineCityList() as $key => $value)
						    		{
						    		    echo '<li>'.$value.'</li>';
						    		}
						    		?>
						    	</ul>
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
						    		<?php 
						    		foreach (Order::getStatusList() as $key => $value)
						    		{
						    		    echo '<li>'.$value.'</li>';
						    		}
						    		?>						    		
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
						     
    <?php 
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
    ]);    
    ?>  
		  	 
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
						       <table cellspacing="0" cellpadding="0">
										<tr>
					                    	<th style="width: 28%;">订单编号：17135929<span>服务类型</span></th>
					                        <th>支付状态</th>
					                        <th></th>  
					                        <th></th>
					                        <th class="m_colo">订单状态</th>
					                    </tr>
					                    <tr>
					                    	<td><label for="">b手机号：</label><span>18612345678</span><br />
					                    	    <label for="">下单渠道：</label><span>App下单</span><br />
					                    	    <label for="">下单时间：</label><span>2015-09-18   9:00-11:00</span><br />
				                    	        <label for="">服务地址：</label><span>北京，中国水科院南小区，9号楼130</span>
					                    	</td>
					                        <td><label for="">手机号：</label><span>18612345678</span><br />
					                    	    <label for="">下单渠道：</label><span>App下单</span><br />
					                    	    <label for="">下单时间：</label><span>2015-09-18   9:00-11:00</span><br />
				                    	        <label for="">服务地址：</label><span>北京，中国水科院南小区，9号楼130</span>
					                    	</td>
					                    	<td><label for="">手机号：</label><span>18612345678</span><br />
					                    	    <label for="">下单渠道：</label><span>App下单</span><br />
					                    	    <label for="">下单时间：</label><span>2015-09-18   9:00-11:00</span><br />
				                    	        <label for="">服务地址：</label><span>北京，中国水科院南小区，9号楼130</span>
					                    	</td>
					                    	<td><label for="">手机号：</label><span>18612345678</span><br />
					                    	    <label for="">下单渠道：</label><span>App下单</span><br />
					                    	    <label for="">下单时间：</label><span>2015-09-18   9:00-11:00</span><br />
				                    	        <label for="">服务地址：</label><span>北京，中国水科院南小区，9号楼130</span>
					                    	</td>
					                    	<td>
					                    		<p><a href="###">查看订单</a></p>
					                    		<p><a href="javascript:;" class="m_tousu">投诉</a></p>
					                    		<p><a href="###">发送短信</a></p>
					                    		<p id="m_tanqu"><a href="javascript:;" class="m_quxiao">取消订单</a></p>
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
		</div>
		  <!------------------取消订单弹出层开始------------------>
		  <div id="HBox" style="display: none;">
			<form action="" method="post" onsubmit="return false;">
				<h1>取消订单</h1>
				<ul class="list">
					<li class="radioLi">
						<strong>* 取消原因 </strong>
						<div class="fl jsRadio">
							<label class="mr10"><input type="radio" name="yin" value="2" class="xuanzhong"/>用户原因</label>
							<label class="mr10"><input type="radio" name="yin" value="2"/>公司原因</label>
						</div>
					</li>
					<li>
						<strong>具体原因 </strong>
						
						<div class="fl js_radio_tab">
							<label class="mr11"><input type="radio" name="yin1" value="2" class="xuanzhong"/>空号错号</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>无人接听</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>用户未预定服务</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>重复订单</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>时间调整</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>用户不需要</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>其他</label>
						</div>
						
						<div class="fl js_radio_tab" style="display: none;">
							<label class="mr11"><input type="radio" name="yin1" value="2"/>用户所在地超范围</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>无服务阿姨</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>无人联系客户</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>爽约（阿姨未接到通知）</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>爽约（阿姨主动爽约）</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>服务体验差</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>其他</label>
						</div>
						
					</li>
						<li>
						<strong>* 备注</strong>
						<div class="fl">
                          <textarea type="text" placeholder="" class="form-control"></textarea>	
                         </div>
					</li>
					<li><input type="submit" value="确认提交" class="submitq" /></li>
				</ul>
			</form>
		</div>
		
		
		<!------------------投诉弹出层开始------------------>
		<div id="HBox2" style="display: none;">
			<form action="" method="post" onsubmit="return false;">
				<h1>投诉</h1>
				<ul class="list">
					<li>
						<strong>* 投诉详情</strong>
						<div class="fl">
                          <textarea type="text" placeholder="" class="form-control"></textarea>	
                         </div>
					</li>
					
					<li class="m_queren" style="display:none;">
					    <strong>* 投诉部门:</strong>
						<div class="fl">
							<p><span>线下运营部 </span><span>投诉类型：迟到早退</span> <span>投诉级别：A</span>
								<a href="javascript:;">修改</a>
							</p>
							<p><span>线下运营部 </span><span>投诉类型：迟到早退</span> <span>投诉级别：A</span>
								<a href="javascript:;">修改</a>
							</p>
							<input type="submit" value="新增" class="submitBtntt m_subm" />
							<input type="submit" value="提交" class="submitBtntt" />
                         </div>
					</li>
					
					<li class="radioLi">
						<strong>* 投诉部门</strong>
						<div class="fl jsRadio">
							<label class="mr10"><input type="radio" name="yin" value="2" class="xuanzhong"/>线下运营部</label>
							<label class="mr10"><input type="radio" name="yin" value="2"/>客服部</label>
							<label class="mr10"><input type="radio" name="yin" value="2"/>线下推广部</label>
							<label class="mr10"><input type="radio" name="yin" value="2"/>公司</label>
							<label class="mr10"><input type="radio" name="yin" value="2"/>财务</label>
							<label class="mr10"><input type="radio" name="yin" value="2"/>系统</label>
							<label class="mr10"><input type="radio" name="yin" value="2"/>活动</label>
						</div>
					</li>
					<li class="m_disd">
						<strong>* 投诉类型</strong>
						
						<div class="fl js_radio_tab">
							<label class="mr11"><input type="radio" name="yin1" value="2" class="xuanzhong"/>无阿姨服务</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>浪费物品</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>物品丢失</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>损坏物品</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>爽约</label>
							<label class="mr11"><input type="radio" name="yin1" value="1"/>未穿工服</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>迟到早退</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>磨洋工</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>打扫不干净</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>工具没带全</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>身上有异味</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>不尊重客户</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>骚扰客户</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>收费不合理</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>不满足客户合理要求</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>与客户发生肢体冲突</label>
						</div>
						
						<div class="fl js_radio_tab" style="display: none;">
							<label class="mr11"><input type="radio" name="yin1" value="1"/>服务态度不满</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>业务知识欠缺</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>未履行服务承诺</label>
						</div>
						<div class="fl js_radio_tab" style="display: none;">
							<label class="mr11"><input type="radio" name="yin1" value="1"/>胡乱承诺</label>
						</div>
						<div class="fl js_radio_tab" style="display: none;">
							<label class="mr11"><input type="radio" name="yin1" value="1"/>公司制度</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>服务流程</label>
						</div>
						<div class="fl js_radio_tab" style="display: none;">
							<label class="mr11"><input type="radio" name="yin1" value="1"/>无法开具发票</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>金额损失</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>发票错误或延时</label>
						</div>
						<div class="fl js_radio_tab" style="display: none;">
							<label class="mr11"><input type="radio" name="yin1" value="1"/>订单丢失</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>订单取消</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>优惠码不可用</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>线上无法支付</label>
							<label class="mr11"><input type="radio" name="yin1" value="2"/>合作平台扣费错误</label>
						</div>
						<div class="fl js_radio_tab" style="display: none;">
							<label class="mr11"><input type="radio" name="yin1" value="1"/>活动方案</label>
						</div>
						
					</li>
					<li class="m_disd">
						<strong>* 投诉级别</strong>
						<div class="fl">
							<label class="mr11"><input type="radio" checked="checked" name="yin1" value="s" />S</label>
							<label class="mr11"><input type="radio" checked="checked" name="yin1" value="a" />A</label>
							<label class="mr11"><input type="radio" checked="checked" name="yin1" value="b" />B</label>
							<label class="mr11"><input type="radio" checked="checked" name="yin1" value="c" />C</label>
						</div>
					</li>
					<li class="m_disd"><input type="submit" value="保存" class="submitBtn" />
					</li>
				</ul>
			</form>
		</div>
		 
<?php 
$this->registerJs('
	    $(function () {
	    	var $el = $(".dialog");
				$el.hDialog(); //默认调用
				//改变宽和高
				$(".m_quxiao").hDialog({width:600,height: 400});
				$(".m_tousu").hDialog({ box:"#HBox2", width:800,height: 600});
				$(".m_tousu").click(function(){
					$(".xuanzhong").attr("checked","checked");
				});
				$(".m_quxiao").click(function(){
					$(".xuanzhong").attr("checked","checked");
				});
				$(".jsRadio label").click(function(){
					var indexval=$(this).index();
					$(this).parents(".radioLi").next("li").children(".js_radio_tab").hide();
					$(this).parents(".radioLi").next("li").children(".js_radio_tab").eq(indexval).show();
				});
				
				$(".submitBtn").click(function(){
					$(".m_queren").show();
					$(".radioLi").hide();
					$(".m_disd").hide();
					$(".m_disd").hide();
					$(".submitBtntt").show();

				});
				
				$(".m_subm").click(function(){
					$(".m_queren").show();
					$(".radioLi").show();
					$(".m_disd").show();
					$(".m_disd").show();
					$(".submitBtntt").hide();
				});
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
