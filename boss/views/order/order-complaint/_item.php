<?php 
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

use boss\models\order\OrderComplaint;

$odercmodel = new OrderComplaint();
?>					
					 <div class="m_from">
				     <div class="m_tab">
				       <div class="m_cek"><input type="checkbox" /></div>
				       <table cellspacing="0" cellpadding="0" border="1">
								<tr class="first">
			                    	<th style="width: 28%;">投&nbsp;&nbsp;诉ID：<?= $model->id;?></th>
			                        <th>投诉阿姨</th>
			                        <th>投诉详情</th>  
			                        <th>处理详情</th>
			                        <th class="m_colo">订单状态</th>
			                    </tr>
			                    <tr>
			                    	<td>订单编号：<?= Html::encode($model->order->order_code); ?><br/>
										客户手机：<?= Html::encode($model->complaint_phone); ?><br/>
										投诉渠道：<?= Html::encode($odercmodel->channel($model->complaint_channel)); ?><br/>
										投诉部门：<?= Html::encode($odercmodel->section($model->complaint_section)); ?><br/>
										投诉级别：<?= Html::encode($odercmodel->level($model->complaint_level)); ?><br/>
										投诉类型：<?= Html::encode($odercmodel->ctype($model->complaint_section,$model->complaint_assortment)); ?><br/>
			                    	</td>
			                        <td>阿姨姓名：<?= Html::encode($model->orderExtWorker->order_worker_name); ?><br/>
										阿姨编号：<?= Html::encode($model->orderExtWorker->worker_id); ?><br/>
										阿姨身份：<?= Html::encode($model->orderExtWorker->order_worker_type_name); ?><br/>
										阿姨手机：<?= Html::encode($model->orderExtWorker->order_worker_phone); ?><br/>
										所属门店：<?= Html::encode($model->orderExtWorker->order_worker_shop_name); ?><br/>
			                    	</td>
			                    	<td>
			                    		<?= Html::encode($model->complaint_content); ?>
			                    	</td>
			                    	<td>
			                    		<?php $content = $odercmodel->getOrderComplaintHandleDetail($model->id); echo $content;?>
			                    	</td>
			                    	<td>
			                    		<p>待确认</p>
			                    		<p><a href="/finance/finance-compensate/create?order_complaint_id=<?= $model->id; ?>">申请赔偿</a></p>
			                    		<p><a href="/order/order-complaint-handle/create?id=<?= $model->id; ?>">处理投诉</a></p>
			                    		<!--p><a href="###">查看关联投诉</a></p> -->
			                    	</td>
			                    </tr>
							</table>
  	                   </div>
  	                  
				    	<div class="clear"></div>
				    </div>