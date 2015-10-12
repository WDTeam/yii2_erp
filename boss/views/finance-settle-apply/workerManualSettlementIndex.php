<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Shop;
use yii\helpers\ArrayHelper;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨结算');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div id = "manualSettle" class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 阿姨搜索</h3>
        </div>

        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                 'type' => ActiveForm::TYPE_HORIZONTAL,
                 //'id' => 'login-form-inline',
                 'method' => 'get',
                 ]);


            ?>
            <div class='col-md-6'>
                <?= $form->field($model, 'workerPhone') ?>
            </div>
            <div class='col-md-2' >
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
            <div class='col-md-4'>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="panel-heading">
                <h3 class="panel-title">阿姨信息</h3>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-2'>
                阿姨姓名
            </div>
            <div class='col-md-2'>
                手机号
            </div>
            <div class='col-md-2'>
                入职日期
            </div>
            <div class='col-md-2'>
                阿姨类型
            </div>
            <div class='col-md-2'>
                结算周期
            </div>
            <div class='col-md-2'>
                上次结算时间
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-2'>
                <?=  $model->workerName; ?>
            </div>
            <div class='col-md-2'>
                <?=  $model->workerPhone; ?>
            </div>
            <div class='col-md-2'>
                <?=  date('Y:m:d H:i:s',$model->workerOnboardTime); ?>
            </div>
            <div class='col-md-2'>
                <?=  $model->workerType; ?>
            </div>
            <div class='col-md-2'>
                <?=  $model->finance_settle_apply_cycle_des; ?>
            </div>
            <div class='col-md-2'>
                <?=  date('Y:m:d H:i:s',$model->latestSettleTime); ?>
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">结算明细</label>
        <?=

            Html::a('结算', ['worker-manual-settlement-done?FinanceSettleApplySearch[worder_id]='.$model->worder_id.'&settle_type='.$model->settle_type.'&review_section='.$model->review_section], ['class' => 'btn btn-success ']);

         ?>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='settleDetail'>
                完成总单量
            </div>
            <div class='settleDetail'>
                现金订单
            </div>
            <div class='settleDetail'>
                收取现金
            </div>
            <div class='settleDetail'>
                非现金订单
            </div>
            <div class='settleDetail'>
                工时费应结
            </div>
            <div class='settleDetail'>
                完成任务
            </div>
            <div class='settleDetail'>
                任务奖励
            </div>
            <div class='settleDetail'>
                小保养订单
            </div>
            <div class='settleDetail'>
                小保养
            </div>
            <div class='settleDetail'>
                应结
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='settleDetail'>
                250
            </div>
            <div class='settleDetail'>
                30
            </div>
            <div class='settleDetail'>
                2000.00
            </div>
            <div class='settleDetail'>
                100
            </div>
            <div class='settleDetail'>
                4000.00
            </div>
            <div class='settleDetail'>
                20
            </div>
            <div class='settleDetail'>
                2000.00
            </div>
            <div class='settleDetail'>
                20
            </div>
            <div class='settleDetail'>
                2000.00
            </div>
            <div class='settleDetail'>
                8000.00
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">订单明细</label>
        </div>
        <div>
            
             <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'order_id',
                 'content'=>function($model,$key,$index)
                        {return  Html::a('<u>'.$model->order_id.'</u>',[Yii::$app->urlManager->createUrl(['order/view/','id' => $model->order_id])],['target'=>'_blank']);}],
            'finance_worker_order_income_type',
            'finance_worker_order_income',
            'finance_worker_order_complete_time:datetime', 
            'order_booked_count', 
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-worker-order-income/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
    ]); Pjax::end(); ?>
        </div>
    </div>
</div>

