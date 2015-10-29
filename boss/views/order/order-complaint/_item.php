<?php 
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
			                    	<td>订   单ID：<?= $model->order_id; ?><br/>
										客户手机：<?= $model->complaint_phone; ?><br/>
										投诉渠道：<?= $odercmodel->channel($model->complaint_channel); ?><br/>
										投诉部门：<?= $odercmodel->section($model->complaint_section); ?><br/>
										投诉级别：<?= $odercmodel->level($model->complaint_level); ?><br/>
										投诉类型：<?= $odercmodel->ctype($model->complaint_section,$model->complaint_type); ?><br/>
			                    	</td>
			                        <td>阿姨姓名：<?= $model->orderExtWorker->order_worker_name; ?><br/>
										阿姨编号：<?= $model->orderExtWorker->worker_id; ?><br/>
										阿姨身份：<?= $model->orderExtWorker->order_worker_type_name; ?><br/>
										阿姨手机：<?= $model->orderExtWorker->order_worker_phone; ?><br/>
										所属门店：<?= $model->orderExtWorker->order_worker_shop_name; ?><br/>
			                    	</td>
			                    	<td>
			                    		<?= $model->complaint_content; ?>
			                    	</td>
			                    	<td>
			                    	</td>
			                    	<td>
			                    		<p>待处理</p>
			                    		<p><a href="/order/order-complaint/create?id=<?= $model->id; ?>">申请赔偿</a></p>
			                    		<!--p><a href="###">查看关联投诉</a></p> -->
			                    	</td>
			                    </tr>
							</table>
  	                   </div>
  	                  
				    	<div class="clear"></div>
				    </div>