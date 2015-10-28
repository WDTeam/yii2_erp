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
										客户手机：<?= $model->orderExtCustomer->order_customer_phone; ?><br/>
										投诉渠道：<?= $model->complaint_channel; ?><br/>
										投诉部门：<?= $model->complaint_section; ?><br/>
										投诉级别：<?= $model->complaint_level; ?><br/>
										投诉类型：<?= $model->complaint_type; ?><br/>
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
			                    		<p>已处理</p>
			                    		<!--p><a href="###">查看关联投诉</a></p> -->
			                    	</td>
			                    </tr>
							</table>
  	                   </div>
  	                  
				    	<div class="clear"></div>
				    </div>