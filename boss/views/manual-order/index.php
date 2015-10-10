<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use common\models\Shop;

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
        <div class="panel-heading"><h3 class="panel-title">当前状态：<span class="badge">忙碌</span></h3></div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr><th>日期</th><th>空闲时间</th><th>忙碌时间</th><th>忙碌时间</th><th>待人工指派</th><th>应指派</th><th>已指派</th><th>指派成功率</th></tr>
                <tr><th>近七日平均</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><th>今日</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            </table>
        </div>
        <div class="panel-body">
                <h4 class="col-sm-4">
                    距用户下单<span id="create_to_current_time" style="font-size: 25px;color: #ff0000;" >23分03秒</span>
                </h4>
                <h4 class="col-sm-4">
                    距服务开始<span class="current_to_begin_service_time" style="font-size: 25px;color: #ff0000;">24时23分03秒</span>
                </h4>
                <h4 class="col-sm-4">
                    请于<span id="count_down" style="font-size: 25px;color: #ff0000;">14分59秒</span>内尽快处理
                </h4>
        </div>
        <div class="panel-body">
            <table class="table table-bordered">
                <tr>
                    <td>
                        <h5 class="col-sm-12">2015-09-18   9:00-11:00</h5>
                        <h5 class="col-sm-12">北京，中国水科院南小区，9号楼1301</h5>
                    </td>
                    <td>
                        <h5 class="col-sm-12">需收取0元</h5>
                        <h5 class="col-sm-12">总金额50元，线上支付40元，优惠券10元</h5>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <h5 class="col-sm-12"> 用户需求：重点打扫厨房 重点打扫卫生间 阿姨不要很多话</h5>
                        <h5 class="col-sm-12">用户备注：自带工具</h5>
                        <h5 class="col-sm-12">客服备注：客户要东北阿姨</h5>
                    </td>
                </tr>
            </table>
        </div>
        <div class="panel-body">
            <div class="worker-search">

                <?php $form = ActiveForm::begin([
                    'type' => ActiveForm::TYPE_VERTICAL,
                    'action' => ['index'],
                    'method' => 'get',
                ]); ?>

                <div class="col-sm-2">
                    <input type="text" name="WorkerSearch[worker_name]" class="form-control" id="workersearch-worker_name" placeholder="阿姨姓名...">
                </div>
                <div class="col-sm-2">
                    <input type="text" name="WorkerSearch[worker_phone]" class="form-control" id="workersearch-worker_phone" placeholder="阿姨电话...">
                </div>
                <div class="col-sm-1">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span>'.Yii::t('app', 'Search'), ['class' => 'btn btn-warning']) ?>
                </div>
                <div class="col-sm-7">
                    <?= Html::button('<span class="glyphicon glyphicon-ban-circle"></span>无法指派', ['class' => 'btn btn-warning','id'=>'can_not_assign']) ?>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
        <div class="panel-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}",
            'columns' => [
                'worker_name',
                [
                    'format' => 'raw',
                    'label' => '门店名称',
                    'value' => function ($dataProvider) {
                        if($dataProvider->shop_id && Shop::findOne($dataProvider->shop_id)){
                            return Shop::findOne($dataProvider->shop_id)->name;
                        }
                    }
                ],
                'worker_phone',
                'worker_idcard',
                [
                    'format' => 'raw',
                    'label' => '阿姨类型',
                    'value' => function ($dataProvider) {
                        return $dataProvider->worker_type ? '自有' : '非自有';
                    },
                    'width' => "100px",
                ],
                [
                    'format' => 'raw',
                    'label' => '阿姨录入时间',
                    'value' => function ($dataProvider) {
                        return date('Y-m-d H:i', $dataProvider->created_ad);
                    },
                    'width' => "120px",
                ],
                [
                    'format' => 'raw',
                    'label' => '操作',
                    'value' => function ($model) {
                        return Html::a('已联系', Yii::$app->urlManager->createUrl(['worker/view', 'id' => $model->id, 'edit' => 't']), [
                            'title' => Yii::t('yii', '已联系'),
                        ]);;
                    },
                    'width' => "120px",
                ]
            ],
        ]); ?>
        </div>
    </div>
</div>
