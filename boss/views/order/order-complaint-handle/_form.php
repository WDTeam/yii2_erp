<?php
use boss\assets\AppAsset;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;


AppAsset::addCss($this, 'css/order_complaint_handle/style.css');

$this->title = '订单投诉管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
	<div class="row">
		<span class="details">投诉详情</span><hr />
	</div>
	<div class="row">
		<table class="table-bordered">
			<tr>
				<th>投诉ID:</th>
				<th>投诉阿姨:</th>
				<th>投诉详情:</th>
			</tr>
			<tr>
				<td>
					订单编号:<br/>
					客户手机:<br/>
					投诉来源:
				</td>
				<td>
					阿姨姓名:<br/>
					阿姨编号:<br/>
					阿姨身份:<br/>
					阿姨手机:<br/>
					所属门店:<br/>
				</td>
				<td></td>
			</tr>
		</table>
	</div>	
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
		<tr>
			<td>11234</td>
			<td>32456</td>
			<td>43256</td>
			<td>24356</td>
			<td>2134567</td>
		</tr>
	</table>
	</div>
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
		<tr>
			<td>wesdrfgh</td>
			<td>sadf</td>
			<td>adfg</td>
		</tr>
	</table>
	</div>
	<div class="row">
		<span class="details">操作</span><hr />
	</div>
	<div class="row">
		<div class="col-md-1 text-left"><span class="">投诉部门:</span></div>
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
		<div class="col-md-1 text-left"><span class="">投诉类型:</span></div>
		<div class="col-md-9">
			<input type="radio" id="1"><label for="1">无</label>
			<input type="radio" id="2"><label for="2">客服部</label>
			<input type="radio" id="3"><label for="3">线下推广部部</label>
			<input type="radio" id="4"><label for="4">公司</label>
			<input type="radio" id="5"><label for="5">财务</label>
			<input type="radio" id="6"><label for="6">系统</label>
			<input type="radio" id="7"><label for="7">活动</label>
			<input type="radio" id="1"><label for="1">无</label>
			<input type="radio" id="2"><label for="2">客服部</label>
			<input type="radio" id="3"><label for="3">线下推广部部</label>
			<input type="radio" id="4"><label for="4">公司</label>
			<input type="radio" id="5"><label for="5">财务</label>
			<input type="radio" id="6"><label for="6">系统</label>
			<input type="radio" id="7"><label for="7">活动</label>
			<input type="radio" id="1"><label for="1">无</label>
			<input type="radio" id="2"><label for="2">客服部</label>
			<input type="radio" id="3"><label for="3">线下推广部部</label>
			<input type="radio" id="4"><label for="4">公司</label>
			<input type="radio" id="5"><label for="5">财务</label>
			<input type="radio" id="6"><label for="6">系统</label>
			<input type="radio" id="7"><label for="7">活动</label>
		</div>
	</div>
	<div class="row mar-top">
		<div class="col-md-1 text-left"><span class="">投诉级别:</span></div>
		<div class="col-md-9">
			<input type="radio" id="1"><label for="1">S</label>
			<input type="radio" id="2"><label for="2">A</label>
			<input type="radio" id="3"><label for="3">B</label>
			<input type="radio" id="4"><label for="4">C</label>
		</div>
	</div>


	<hr>
	<div class="row mar-top">
		<div class="col-md-12 text-left"><span class="">阿姨处理:</span><span class="yellow-text">封号一天</span></div>
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
</div>



