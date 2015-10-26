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
<div class="order-index">
    <div class="panel panel-info">
        <div class="panel-heading"><h3 class="panel-title">当前状态：<span id="work_status" class="badge">休息</span></h3></div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr><th>日期</th><th>空闲时间</th><th>忙碌时间</th><th>忙碌时间</th><th>待人工指派</th><th>应指派</th><th>已指派</th><th>指派成功率</th></tr>
                <tr><th>近七日平均</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><th>今日</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            </table>
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
                        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
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
