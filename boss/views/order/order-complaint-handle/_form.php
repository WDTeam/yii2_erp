<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = '订单投诉管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
.contented{
	display: none;
}
</style>
投诉详情
<table>
	<thead>
		<tr>
			<th>投&nbsp;&nbsp;&nbsp;&nbsp;诉ID:<?= Html::encode($orderComplaintModel->id); ?></th>
			<th>投诉阿姨:</th>
			<th>投诉详情:</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				订单编号:<?= Html::encode($orderComplaintModel->order->order_code); ?><br/>
				客户手机:<?= Html::encode($orderComplaintModel->complaint_phone); ?><br/>
				投诉来源:<?= Html::encode($orderComplaintModel->complaint_channel); ?>
			</td>
			<td>
				阿姨姓名:<?= Html::encode($orderComplaintModel->orderExtWorker->order_worker_name);?><br/>
				阿姨编号:<?= Html::encode($orderComplaintModel->orderExtWorker->worker_id);?><br/>
				阿姨身份:<?= Html::encode($orderComplaintModel->orderExtWorker->order_worker_type_name);?><br/>
				阿姨手机:<?= Html::encode($orderComplaintModel->orderExtWorker->order_worker_phone);?><br/>
				所属门店:<?= Html::encode($orderComplaintModel->orderExtWorker->order_worker_shop_name);?><br/>
			</td>
			<td><? Html::encode($content); ?></td>
		</tr>
	</tbody>
</table>
操作记录
<table>
	<thead>
		<tr>
			<th>时间</th>
			<th>操作人</th>
			<th>操作项</th>
			<th>由</th>
			<th>变更为</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>
<?php if(($orderComplaintModel->complaint_status != 1) && !empty($ochModel) && is_array($ochModel)){?>
处理方案
<table>
	<thead>
		<tr>
			<th>时间</th>
			<th>操作人</th>
			<th>处理方案</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($ochModel as $key=>$object){?>
		<tr>
			<td><?= Html::encode(date("Y-m-d H:i:s",$object->created_at));?></td>
			<td><?= Html::encode($object->handle_operate); ?></td>
			<td><?= Html::encode($object->handle_plan); ?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
<?php }?>
操作
<hr>
 <?php $form = ActiveForm::begin(['action' => ['order/order-complaint-handle/create'],'method'=>'post','type'=>ActiveForm::TYPE_HORIZONTAL]); 
        ?>
        <input type="hidden" value="<?= $orderComplaintModel->id;?>" name="OrderComplaint[id]">
<div>
		投诉部门:<?php if(!empty($complaintSection)){foreach ($complaintSection as $keycs=>$valcs){?>
		<?= $valcs; ?><input type="radio" name="OrderComplaint[complaint_section]"  value="<?= $keycs; ?>" <?php if($keycs == $orderComplaintModel->complaint_section){?>checked="checked"<?php }?>>
		<?php }}?>
</div>
<div style="width: 900px; height:100px;">
		投诉类型:<?php if(!empty($complaintAssortment)){foreach ($complaintAssortment as $keyca=>$valca){?>
	  	<ul id="assortment<?= $keyca; ?>" class="contented" style="color:#999999;<?php if($keyca == $orderComplaintModel->complaint_section){?>display:block;<?php }?>" >
	  	<?php foreach ($valca as $keyca2=>$valca2){?>
	  	<li style="float:left;">
	  	<?= $valca2;?><input type="radio" name="OrderComplaint[complaint_assortment]" value="<?= $keyca2; ?>" <?php if($keyca2 == $orderComplaintModel->complaint_assortment){?>checked="checked"<?php }?>>
	  	</li>
	   <?php }?>
	   </ul>
	   <?php }}?>
</div>
<hr>
<div>
	   投诉级别:<?php if(!empty($complaintLevel)){foreach ($complaintLevel as $keycl=>$valcl){?>
		<?= $valcl; ?><input type="radio" name="OrderComplaint[complaint_level]" value="<?= $keycl;?>"<?php if($keycl == $orderComplaintModel->complaint_level){?>checked="checked"<?php }?>>
	  <?php }}?>
</div>
	  <hr>
阿姨处理
<hr>
<div>
<input type="hidden" value="<?= $orderComplaintModel->id;?>" name="OrderComplaintHandle[order_complaint_id]">
投诉状态:
<?php if(!empty($complaintStatus)){foreach ($complaintStatus as $keycs=>$valcs){?>
		<?= $valcs; ?><input type="radio" name="OrderComplaint[complaint_status]" value="<?= $keycs;?>">
	  <?php }}?>
</div>
<div>
处理部门:
<?php if(!empty($complaintSection)){foreach ($complaintSection as $keycls=>$valcls){?>
		<?= $valcls; ?><input type="radio" name="OrderComplaintHandle[handle_section]" value="<?= $keycls;?>">
		<?php }}?>
</div>
<div>
处理方案:
	<textarea rows="5" cols="40" name="OrderComplaintHandle[handle_plan]"></textarea>
</div>
<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>
<?php 
$this->registerJs('
	    $(function () {
			$("input[name=\"OrderComplaint[complaint_section]\"]").click(function(){
				var id= $(this).val();
				$(".contented").hide();
				$("#assortment"+id).show();
			});

        })
    ');

?>