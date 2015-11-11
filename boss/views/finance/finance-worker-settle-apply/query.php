<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨结算查询');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i>结算查询</h3>
        </div>

        <div class="panel-body">
            <?php

            echo $this->render('_search', ['model' => $searchModel]);
            ?>

        </div>
        <div class="panel-heading">
            <h3 class="panel-title">结算列表</h3>
        </div>
        <div>
            
            <?php Pjax::begin(); echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [ 'header' => Yii::t('app', '结算编号'),'attribute'=>'finance_worker_settle_apply_code'],
                [ 'header' => Yii::t('app', '阿姨电话'),'attribute'=>'worker_tel'],
                [ 'header' => Yii::t('app', '阿姨姓名'),'attribute'=>'worker_name'],
                [ 'header' => Yii::t('app', '阿姨类型'),'content'=>function($model,$key,$index){return $model->getWorkerTypeDes($model->worker_type_id,$model->worker_identity_id);}],
                [ 'header' => Yii::t('app', '结算类型'),'attribute'=>'finance_worker_settle_apply_cycle_des'],
                ['header' => Yii::t('app', '申请时间'),'attribute'=>'created_at','content'=>function($model,$key,$index){return date('Y-m-d H:i:s',$model->created_at);}],
                [ 'header' => Yii::t('app', '结算周期'),'attribute'=>'finance_worker_settle_apply_starttime','content'=>function($model,$key,$index){return date('Y-m-d',$model->finance_worker_settle_apply_starttime).'至<br>'.date('Y-m-d',$model->finance_worker_settle_apply_endtime);}],
                [ 'header' => Yii::t('app', '订单总工时'),'attribute'=>'finance_worker_settle_apply_man_hour'], 
                [ 'header' => Yii::t('app', '工时费小计'),'attribute'=>'finance_worker_settle_apply_order_money'], 
                [ 'header' => Yii::t('app', '底薪补贴'),'attribute'=>'finance_worker_settle_apply_base_salary_subsidy'], 
                [ 'header' => Yii::t('app', '完成任务数'),'attribute'=>'finance_worker_settle_apply_task_count'], 
                [ 'header' => Yii::t('app', '完成任务奖励'),'attribute'=>'finance_worker_settle_apply_task_money'], 
                [ 'header' => Yii::t('app', '应结合计'),'attribute'=>'finance_worker_settle_apply_money_except_deduct_cash'], 
                [ 'header' => Yii::t('app', '扣款小计'),'attribute'=>'finance_worker_settle_apply_money_deduction'], 
                [ 'header' => Yii::t('app', '本次应结合计'),'attribute'=>'finance_worker_settle_apply_money_except_cash'], 
                [ 'header' => Yii::t('app', '现金订单'),'attribute'=>'finance_worker_settle_apply_order_cash_count'], 
                [ 'header' => Yii::t('app', '已收现金小计'),'attribute'=>'finance_worker_settle_apply_order_cash_money'], 
                [ 'header' => Yii::t('app', '本次应付合计'),'attribute'=>'finance_worker_settle_apply_money'],    
                ['header' => Yii::t('app', '备注'),'attribute'=>'comment','content'=>function($model,$key,$index){return $model->comment == null?'':$model->comment;}],
                [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['/finance/finance-worker-settle-apply/self-fulltime-worker-settle-view', 'FinanceWorkerSettleApplySearch[id]' => $model->id],[]), [
                            'title' => Yii::t('yii', '查看'),'data-pjax'=>'0','target' => '_blank',
                        ]);
                    },
                    ],
                ],
            ],
            'responsive'=>true,
            'hover'=>true,
            'condensed'=>true,
            'floatHeader'=>false,
                        
             'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'after'=>false,
            'showFooter'=>false
        ],
        ]); Pjax::end(); ?>
        </div>
    </div>
    <p>
    </p>
