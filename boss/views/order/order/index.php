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
                    echo $this->render('_search', ['searchModel' => $searchModel, 'searchParas' => $searchParas]);
                    ?>
						
		  	 	 	<!---------------------订单筛选条件-------------------->
                    <?php            
                    echo $this->render('_filter', ['searchModel' => $searchModel]);
                    ?>

<!-- 排序，可开发票筛选，Excel导出稍后做

				    <div class="m_from">
				    	<ul class="lis liss" id="list">
				    		<li>按下单时间 ↑</li>
				    		<li>按服务时间 ↑</li>
				    	</ul>
				    	<h6><input type="checkbox" /><a href="javascript">可开发票</a></h6>
				    	<p class="m_daoc"><a href="javascript:;">Excel导出</a></p>
				    	<div class="clear"></div>
				     </div>
-->				    
					 <div class="m_from">						     
					    <?php
					    echo ListView::widget([
					        'dataProvider' => $dataProvider,
					        'itemView' => '_item',
					    ]);
					    ?>  							    
				    	<div class="clear"></div>
				    </div>                    

				    
<!-- 周期订单暂时还不支持					    
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
					                    		<p><a href="javascript:;" class="m_meagg">发送短信</a></p>
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
-->					         

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
		
		<!------------------取消订单弹出层开始------------------>
		  <div id="HBox" style="display: none;">
			<form action="" method="post" onsubmit="return false;">
				<h1>取消订单</h1>
				<ul class="list">
					<li class="radioLi">
						<strong>* 取消原因 </strong>
						<div class="fl jsRadio">
							<?php foreach($cancelCause as $k=>$v):?>
							<label class="mr10"><input type="radio" name="radio_cancelType" value="<?=$k;?>"/><?=$v;?></label>
							<?php endforeach;?>
						</div>
					</li>
					<li>
						<strong>具体原因 </strong>
						
						<div class="fl js_radio_tab">
							<?php foreach($cancelCustomerCause as $k=>$v):?>
							<label class="mr11"><input type="radio" name="radio_cancelDetailType" value="<?=$k;?>" /><?=$v;?></label>
							<?php endforeach;?>
						</div>
						
						<div class="fl js_radio_tab" style="display: none;">
							<?php foreach($cancelCompanyCause as $k=>$v):?>
							<label class="mr11"><input type="radio" name="radio_cancelDetailType" value="<?=$k;?>"/><?=$v;?></label>
							<?php endforeach;?>
						</div>
						
					</li>
						<li>
						<strong>* 备注</strong>
						<div class="fl">
                          <textarea id="text_CancelNote" type="text" placeholder="" class="form-control"></textarea>	
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
                          <textarea id="complaint_detail" type="text" placeholder="" class="form-control"></textarea>	
                         </div>
					</li>
					
					<li class="m_queren" style="display:none;">
					    <strong>投诉部门列表:</strong>
						<div class="fl">
							<input type="submit" value="新增" class="submitBtntt m_add" />
							<input type="submit" value="提交" class="submitBtntt m_submit" />
                         </div>
					</li>
					
					<li class="radioLi">
						<strong>* 投诉部门</strong>
						<div class="fl jsRadio">
    						<?php
    						  $i = 0;
    						  foreach (OrderComplaint::Department() as $key => $value)
    						  {
    						      if ($i == 0)
    						          echo '<label class="mr10"><input type="radio" class="xuanzhong" name="radio_department" value="'.$key.'"/>'.$value.'</label>';
    						      else
    						          echo '<label class="mr10"><input type="radio" name="radio_department" value="'.$key.'"/>'.$value.'</label>';
    						      $i++;
    						  }    						  
    						?>
						</div>
					</li>
					<li class="m_disd">
						<strong>* 投诉类型</strong>
						
						<?php 
						  $i = 0;
						  foreach (OrderComplaint::ComplaintTypes() as $key => $value)
						  {
						      if ($i == 0)
						          echo '<div class="fl js_radio_tab">';
						      else
						          echo '<div class="fl js_radio_tab" style="display: none;">';
						      foreach ($value as $type_id => $type_name)
						      {
						          echo '<label class="mr11"><input type="radio" name="radio_complaint_type" value="'.$type_id.'"/>'.$type_name.'</label>';
						      }
						      echo '</div>';
						      $i++;
						  }
						?>						
					</li>
					<li class="m_disd">
						<strong>* 投诉级别</strong>
						<div class="fl">
    						<?php 
    						  $i = 0;
    						  foreach (OrderComplaint::ComplaintLevel() as $key => $value)
    						  {
   						          echo '<label class="mr11"><input type="radio" checked="checked" name="radio_complaint_level" value="'.$key.'" />'.$value.'</label>';
    						  }
    						?>						
						</div>
					</li>
					<li class="m_disd"><input type="submit" value="保存" class="submitBtn" />
					</li>
				</ul>
			</form>
		</div>
		
		<!------------------发送短信弹出层开始------------------>
		<div id="HBox3" style="display: none;">
			<form action="" method="post" onsubmit="return false;">
				<h1>投诉</h1>
				<ul class="list">
					<li class="radioLi">
						<strong>通知对象</strong>
						<div class="fl jsRadio">
							<label class="mr10"><input type="radio" name="yin" value="2" class="xuanzhong"/>用户</label>
							<label class="mr10"><input type="radio" name="yin" value="2"/>阿姨</label>
						</div>
					</li>
					<li>
						<strong>短信内容</strong>
						<div class="fl">
                          <textarea type="text" placeholder="" class="form-control"></textarea>	
                         </div>
					</li>
					
					<li class="m_disd"><input type="submit" value="保存" class="submitBtn" />
					</li>
				</ul>
			</form>
		</div>