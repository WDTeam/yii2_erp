<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\ManualOrderSerach $searchModel
 */

$this->title = Yii::t('order', 'ManualOrder');
$this->params['breadcrumbs'][] = $this->title;
?>
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

<div class="order-index">
    <div class="panel panel-info">
        <div class="panel-heading"><h3 class="panel-title">当前状态：<span id="work_status" class="badge badge-warning">休息</span></h3></div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr><th>日期</th><th>空闲时间</th><th>忙碌时间</th><th>忙碌时间</th><th>待人工指派</th><th>应指派</th><th>已指派</th><th>指派成功率</th></tr>
                <tr>
                    <th>近七日平均</th>
                    <td id="free_avg"></td>
                    <td id="busy_avg"></td>
                    <td id="rest_avg"></td>
                    <td></td>
                    <td><?= Html::encode( number_format($model->dispatcher_kpi_obtain_count_avg,2)) ?></td>
                    <td><?= Html::encode( number_format($model->dispatcher_kpi_assigned_count_avg,2)) ?></td>
                    <td><?= Html::encode( number_format($model->dispatcher_kpi_assigned_rate_avg,2)) ?></td>
                </tr>
                <tr>
                    <th>今日</th>
                    <td id="free" value=""></td>
                    <td id="busy" value=""></td>
                    <td id="rest" value=""></td>
                    <td id="nonAss"><?= Html::encode($model->non_assign_order_count) ?></td>
                    <td id="obtain_count"></td>
                    <td id="assigned_count"></td>
                    <td id="assigned_rate"></td>
                </tr>
            </table>
        </div>
        <div class="row" style="display: none;" >
            <div class="row">
                <div class="col-md-2"><button class="btn btn-default" id="startId" name="startId" hidden>开工啦</button></div>
            </div>
            <div class="row" >
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
            <div class="col-md-3"><nobr>
                    <button class="btn btn-default" id="endId" name="endId">收工啦</button><span style="padding:2px;">
           <button class="btn btn-default" id="restEndId" name="restEndId">收工啦</button><span style="padding:2px;">
           <button class="btn btn-default" id="restId" name="restId">小休</button><span style="padding:2px;">
            <button class="btn btn-default" id="acceptId" name="acceptId">我要接活(<span id="rundownId" style="color:red">10</span>秒)</button><span style="padding:2px;">
          <button class="btn btn-default" id="restAcceptId" name="restAcceptId">我要接活</button>
        <nobr></div>
        </div>
        <div id="work_console" class="panel-body">
            <?= Html::button('开工啦', ['class' => 'btn btn-warning','id'=>'start_work']) ?>
        </div>
        <div id="order_assign" style="display:none; ">
            <div class="panel-body">
                <h4 class="col-sm-4">
                    距用户下单<span id="create_to_current_time" style="font-size: 25px;color: #ff0000;" ></span>
                </h4>
                <h4 class="col-sm-4">
                    距服务开始<span id="current_to_begin_service_time" style="font-size: 25px;color: #ff0000;"></span>
                </h4>
                <h4 class="col-sm-4">
                    请于<span id="count_down" style="font-size: 25px;color: #ff0000;"></span>内尽快处理
                </h4>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tr>
                        <td width="500">
                            <h5 class="col-sm-12" id="booked_time_range"></h5>
                            <h5 class="col-sm-12" id="order_address"></h5>
                        </td>
                        <td>
                            <h5 id="must_pay_info" class="col-sm-12"></h5>
                            <h5 id="pay_info" class="col-sm-12"></h5>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h5 class="col-sm-12" id="order_customer_need"></h5>
                            <h5 class="col-sm-12" id="order_customer_memo"></h5>
                            <h5 class="col-sm-12" id="order_cs_memo"></h5>
                            <h5 class="col-sm-12" id="order_check_worker"></h5>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="panel-body">
                <div class="worker-search">
                    <div class="col-sm-4">
                        <input type="text" name="worker_search" class="form-control" id="worker_search_input" placeholder="阿姨姓名或电话...">
                    </div>
                    <div class="col-sm-1">
                        <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span>'.Yii::t('app', 'Search'), ['class' => 'btn btn-warning','id'=>'worker_search_submit']) ?>
                    </div>
                    <div class="col-sm-7">
                        <?= Html::button('<span class="glyphicon glyphicon-ban-circle"></span>无法指派', ['class' => 'btn btn-warning','id'=>'can_not_assign']) ?>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <table id="worker_list" class="table table-bordered">
                    <thead>
                        <tr><th>阿姨姓名</th><th>阿姨电话</th><th>所在店铺</th><th>阿姨身份</th><th>当日订单</th><th>拒单率</th><th>阿姨标签</th><th>接单状态</th><th>操作</th></tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="worker_refuse_modal" tabindex="-1" role="dialog" aria-labelledby="worker_refuse_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="关闭"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="worker_refuse_modal_label">请选择拒单原因</h4>
            </div>
            <div class="modal-body">
                <div class="radio">
                    <label>
                        <input type="radio" name="worker_refuse_memo" id="worker_refuse_memo1" value="距离太远"> 距离太远
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="worker_refuse_memo" id="worker_refuse_memo3" value="有其它活儿"> 有其它活儿
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="worker_refuse_memo" id="worker_refuse_memo3" value="0"> <input type="text" class="form-control" id="worker_refuse_memo_other" placeholder="其它" />
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" id="worker_refuse_memo_submit" class="btn btn-warning">确定</button>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJsFile('/js/manual_order.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]); ?>
<?php $this->registerJsFile('/js/dispatcher/index.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]); ?>
