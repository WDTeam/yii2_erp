<?php
use boss\assets\AppAsset;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;


AppAsset::addCss($this, 'css/order_complaint_handle/style.css');

$this->title = '订单投诉管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
li{
list-style-type:none;
}
.contented{
	display: none;
}
.radio{
	float:left;
}
.control-label{
	display:none;
}
</style>
<div class="container">
	<div class="row">
		<span class="details">投诉详情</span><hr />
	</div>
	<div class="row">
		<table class="table-bordered">
			<tr>
				<th>投&nbsp;诉ID:<?= Html::encode($orderComplaintModel->id); ?></th>
				<th>投诉阿姨:</th>
				<th>投诉详情:</th>
			</tr>
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
				<td><?= Html::encode($content); ?></td>
			</tr>
		</table>
	</div>	
<?php if(!empty($ochlogModel) && is_array($ochlogModel)){?>
	<div class="row">
		<span class="details">操作记录</span><hr />
	</div>	
	<div class="row">
		<table class="table-bordered">
		<tr>
			<th>时间</th>
			<th>操作人</th>
			<th>操作项</th>
			<th>由</th>
			<th>变更为</th>
		</tr>
		<?php foreach ($ochlogModel as $keyoch=>$modeloch){?>
		<tr>
			<td><?= Html::encode(date("Y-m-d H:i:s",$modeloch->created_at)); ?></td>
			<td><?= Html::encode($modeloch->handle_operate);?></td>
			<td><?= Html::encode($modeloch->handle_option); ?></td>
			<td><?= Html::encode($modeloch->status_before); ?></td>
			<td><?= Html::encode($modeloch->status_after); ?></td>
		</tr>
		<?php }?>
	</table>
	</div>
<?php }?>	
<?php if(($orderComplaintModel->complaint_status != 1) && !empty($ochModel) && is_array($ochModel)){?>	
	<div class="row">
		<span class="details">处理方案</span><hr />
	</div>
	<div class="row">
		<table class="table-bordered">
		<tr>
			<th>时间</th>
			<th>操作人</th>
			<th>处理方案</th>
		</tr>
		<?php foreach ($ochModel as $key=>$object){?>
		<tr>
			<td width="20%"><?= Html::encode(date("Y-m-d H:i:s",$object->created_at));?></td>
			<td width="20%"><?= Html::encode($object->handle_operate); ?></td>
			<td><?= Html::encode($object->handle_plan); ?></td>
		</tr>
			<?php }?>
	</table>
	</div>
<?php }?>
	<div class="row">
		<span class="details">操作</span><hr />
	</div>
 <?php $form = ActiveForm::begin(['action' => ['order/order-complaint-handle/create'],'method'=>'post','type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
    <input type="hidden" value="<?= $orderComplaintModel->id;?>" name="OrderComplaint[id]">
	<div class="row">
		<div class="col-md-1 text-left"><span class="">投诉部门:</span></div>
		<div class="col-md-9">
		<?php if(!empty($complaintSection)){foreach ($complaintSection as $keycs=>$valcs){?>
		<input type="radio" name="OrderComplaint[complaint_section]"  value="<?= $keycs; ?>" <?php if($keycs == $orderComplaintModel->complaint_section){?>checked="checked"<?php }?>>
		<label><?= $valcs; ?></label>
		<?php }}?>
		</div>
	</div>
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">投诉类型:</span></div>
		<div class="col-md-9">
		<?php if(!empty($complaintAssortment)){foreach ($complaintAssortment as $keyca=>$valca){?>
	  	<ul style="padding:0 !important;" id="assortment<?= $keyca; ?>" class="contented" style="color:#999999;<?php if($keyca == $orderComplaintModel->complaint_section){?>display:block;<?php }?>" >
	  	<?php foreach ($valca as $keyca2=>$valca2){?>
	  	<li style="float:left;">
	  	<input type="radio" name="OrderComplaint[complaint_assortment]" value="<?= $keyca2; ?>" <?php if($keyca2 == $orderComplaintModel->complaint_assortment){?>checked="checked"<?php }?>>
	  	<label><?= $valca2;?></label>
	  	</li>
	   <?php }?>
	   </ul>
	   <?php }}?>
		</div>
	</div>
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">投诉级别:</span></div>
		<div class="col-md-9">
			<?php if(!empty($complaintLevel)){foreach ($complaintLevel as $keycl=>$valcl){?>
			<input type="radio" name="OrderComplaint[complaint_level]" value="<?= $keycl;?>"<?php if($keycl == $orderComplaintModel->complaint_level){?>checked="checked"<?php }?>>
			<label><?= $valcl; ?></label>
		  <?php }}?>
		</div>
	</div>

	<!-- 
	<hr>
	<div class="row mar-top">
		<div class="col-md-12 text-left"><span class="">阿姨处理:</span><span class="yellow-text">封号一天</span></div>
	</div>
	<hr> -->
	<input type="hidden" value="<?= $orderComplaintModel->id;?>" name="OrderComplaintHandle[order_complaint_id]">
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">投诉状态:</span></div>
		<div class="col-md-9">
		<?php if(!empty($complaintStatus)){foreach ($complaintStatus as $keycs=>$valcs){?>
		<input type="radio" name="OrderComplaint[complaint_status]" value="<?= $keycs;?>" <?php if($keycs == $orderComplaintModel->complaint_status){?>checked="checked"<?php }?>>
		<label><?= $valcs; ?></label>
	  	<?php }}?>
		</div>
	</div>
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">处理部门:</span></div>
		<div class="col-md-9">
		<?php echo $form->field($model,"handle_section")->radioList($complaintSection);?>
		<!-- 
		<?php if(!empty($complaintSection)){foreach ($complaintSection as $keycls=>$valcls){?>
		<input type="radio" name="OrderComplaintHandle[handle_section]" value="<?= $keycls;?>">
			<label><?= $valcls; ?></label>
		<?php }}?> -->
		</div>
	</div>
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">处理方案:</span></div>
		<div class="col-md-9">
		   <?php echo $form->field($model,"handle_plan")->textarea(["rows"=>6,"colos"=>100])?>
			<!--textarea name="OrderComplaintHandle[handle_plan]" id="" cols="100" rows="6"></textarea -->
		</div>
	</div>
	<div class="row mar-top">
	<?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>
	</div>
	
<!--

	<hr>
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">客户赔付:</span></div>
		<div class="col-md-11">
			<select name="" id="">
				<option value="1">1</option>
				<option value="1">1</option>
				<option value="1">1</option>
			</select>
		</div>
	</div>
	<hr>
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">订单状态:</span></div>
		<div class="col-md-9">
			<input type="radio" id="1"><label for="1">待处理</label>
		</div>
	</div>
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">处理部门:</span></div>
		<div class="col-md-9">
			<input type="radio" id="1"><label for="1">线下运营部</label>
			<input type="radio" id="2"><label for="2">客服部</label>
			<input type="radio" id="3"><label for="3">线下推广部部</label>
			<input type="radio" id="4"><label for="4">公司</label>
			<input type="radio" id="5"><label for="5">财务</label>
			<input type="radio" id="6"><label for="6">系统</label>
			<input type="radio" id="7"><label for="7">活动</label>
		</div>
	</div>
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">处理方案:</span></div>
		<div class="col-md-9">
			<textarea name="" id="" cols="100" rows="6"></textarea>
		</div>
	</div>
	<div class="row mar-top">
		<input class="btn" type="button" value="确定">
	</div>
	-->
</div>
<?php 
$this->registerJs('
	    $(function () {
		//控制投诉类型的显示与隐藏
			$("input[name=\"OrderComplaint[complaint_section]\"]").click(function(){
				var id= $(this).val();
				$(".contented").hide();
				$("#assortment"+id).show();
			});
        })
    ');

?>