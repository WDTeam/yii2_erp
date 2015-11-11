<?php
/*
 * BOSS 人工派单
 * @author 张仁钊
 * @link http://boss.1jiajie.com/auto-assign/
 * @copyright Copyright (c) 2015 E家洁 LLC
*/
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use boss\assets\AppAsset;


$this->title = Yii::t('order', '人工派单');
$this->params['breadcrumbs'][] = ['label' => Yii::t('order', '订单'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- 新增样式表begin -->
<script type="text/javascript" src="../js/dispatcher/jquery.js"></script>
<script type="text/javascript" src="../js/dispatcher/index.js"></script>

<style type="text/css">
    hr { margin-top: 0;}
    .col-md-4 {width: 27%;}
    .col-md-6 {width: 54%;}
    .margin-l-30 {margin-left: 30px;}
    .margin-l-36 {margin-left: 33px;}
</style>
<!-- end -->
<!--初始化界面数据-->
<input id="idd" name="idd" value="<?= Html::encode($model->id ) ?>" hidden />
<input id="system_user_id" name="system_user_id" value="<?= Html::encode($model->system_user_id ) ?>" hidden />
<input id="dispatcher_kpi_status" name="dispatcher_kpi_status" value="<?= Html::encode($model->dispatcher_kpi_status ) ?>" hidden />
<input id="dispatcher_kpi_date" name="dispatcher_kpi_date" value="<?= Html::encode($model->dispatcher_kpi_date ) ?>" hidden />
<input id="free_time_avg" name="free_time_avg" value="<?= Html::encode($model->dispatcher_kpi_free_time_avg ) ?>" hidden />
<input id="busy_time_avg" name="busy_time_avg" value="<?= Html::encode($model->dispatcher_kpi_busy_time_avg ) ?>" hidden />
<input id="rest_time_avg" name="rest_time_avg" value="<?= Html::encode($model->dispatcher_kpi_rest_time_avg ) ?>" hidden />
<input id="free_time" name="free_time" value="<?= Html::encode($model->dispatcher_kpi_free_time ) ?>" hidden />
<input id="busy_time" name="busy_time" value="<?= Html::encode($model->dispatcher_kpi_busy_time ) ?>" hidden />
<input id="rest_time" name="rest_time" value="<?= Html::encode($model->dispatcher_kpi_rest_time ) ?>" hidden />

<input id="obtainId" name="obtainId" value="<?= Html::encode($model->dispatcher_kpi_obtain_count ) ?>" hidden />
<input id="assignedId" name="assignedId" value="<?= Html::encode($model->dispatcher_kpi_assigned_count ) ?>" hidden />
<input id="assRateId" name="assRateId" value="<?= Html::encode($model->dispatcher_kpi_assigned_rate ) ?>" hidden />
<div id="id" hidden></div>
<div class="row" style="font-size:18px;"  align="right">
    <div class="col-md-6  form-inline">
        <label style="color:red" >状态：</label><span id="statusId"></span><span id="newStatusId" hidden></span>
    </div>
</div>
<!--界面列表-->
<div class="container">
    <table class="table table-hover table-bordered" style="font-size:14px;" >
        <thead>
        <tr >
            <th>日期</th>
            <th>空闲时间</th>
            <th>忙碌时间</th>
            <th>小休时间</th>
            <th>待人工指派</th>
            <th>应指派</th>
            <th>已指派</th>
            <th>指派成功率</th>
        </tr>
        </thead>
        <tbody id="tbody">
        <tr>
            <td>近7日平均</td>
            <td id="free_avg"></td>
            <td id="busy_avg"></td>
            <td id="rest_avg"></td>
            <td></td>
            <td><?= Html::encode( number_format($model->dispatcher_kpi_obtain_count_avg,2)) ?></td>
            <td><?= Html::encode( number_format($model->dispatcher_kpi_assigned_count_avg,2)) ?></td>
            <td><?= Html::encode( number_format($model->dispatcher_kpi_assigned_rate_avg,2)) ?></td>
        </tr>
        <tr>
            <td>今日</td>
            <td id="free" value=""></td>
            <td id="busy" value=""></td>
            <td id="rest" value=""></td>
            <td id="nonAss"><?= Html::encode($model->non_assign_order_count) ?></td>
            <td id="obtain_count"></td>
            <td id="assigned_count"></td>
            <td id="assigned_rate"></td>
        </tr>
        </tbody>
    </table>
    <!--界面按钮-->
    <div class="row"  >
        <div class="row"  id="" >
            <div class="col-md-2"><button class="btn btn-default" id="startId" name="startId" hidden>开工啦</button></div>
        </div>
        <div class="row"  id="" >
            <div class="col-md-2"><button class="btn btn-default" id="waitId" name="waitId" hidden>系统分配订单，请稍后~</button></div>
        </div>
        <div class="row" style="font-size:14px;" align="center" id="dispatchId" hidden><nobr>
            <div class="col-md-6  form-inline"><span style="padding:50px;">
                <label>距下单时间</label><span style="padding:10px;">1小时20分</span>
                <label>距服务时间</label><span style="padding:10px;">1小时20分</span>
                <label>请于</label><span id="rundown15Id" hidden>900</span><span id="rundown15Name" style="color:red">15分0秒</span><label>内处理完成</label>
               <span style="padding:100px;">
                <button class="btn btn-default" id="dispatchedId" name="dispatchedId">指派成功</button><span style="padding:2px;">
                <button class="btn btn-default" id="nonDispatchId" name="nonDispatchId">无法指派</button><span style="padding:2px;">
           </div> <nobr>
        </div>
        <div class="col-md-3">
            <nobr>
           <button  class="btn btn-default" id="endId" name="endId">收工啦</button><span style="padding:2px;">
           <button  class="btn btn-default" id="restEndId" name="restEndId">收工啦</button><span style="padding:2px;">
           <button  class="btn btn-default" id="restId" name="restId">小休</button><span style="padding:2px;">
           <button class="btn btn-default" id="acceptId" name="acceptId">我要接活(<span id="rundownId" style="color:red">10</span>秒)</button><span style="padding:2px;">
           <button   class="btn btn-default" id="restAcceptId" name="restAcceptId">我要接活</button>
            <nobr>
        </div>
    </div>
    <hr/>
</div>


