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
                'worker_tel',
                'worker_name',
                [ 'header' => Yii::t('app', '阿姨类型'),'content'=>function($model,$key,$index){return $model->getWorkerTypeDes($model->worker_type_id,$model->worker_identity_id);}],
                'finance_settle_apply_cycle_des',
                ['attribute'=>'created_at','content'=>function($model,$key,$index){return Html::a(date('Y:m:d H:i:s',$model->created_at),'#');}],
                [ 'header' => Yii::t('app', '结算周期'),'content'=>function($model,$key,$index){return date('Y-m-d',$model->finance_settle_apply_starttime).'至<br>'.date('Y-m-d',$model->finance_settle_apply_endtime);}],
                'finance_settle_apply_man_hour', 
                'finance_settle_apply_order_money', 
                'finance_settle_apply_base_salary_subsidy', 
                'finance_settle_apply_task_count', 
                'finance_settle_apply_task_money', 
                'finance_settle_apply_money_except_deduct_cash', 
                'finance_settle_apply_money_deduction', 
                'finance_settle_apply_money_except_cash', 
                'finance_settle_apply_order_cash_count', 
                'finance_settle_apply_order_cash_money', 
                'finance_settle_apply_money', 
                ['attribute'=>'finance_settle_apply_status',
                    'content'=> function($model,$key,$index){return $model->getSettleApplyStatusDes($model->finance_settle_apply_status);} ],     
                ['attribute'=>'comment','content'=>function($model,$key,$index){return $model->comment == null?'':$model->comment;}],
                ['attribute'=>'updated_at','content'=>function($model,$key,$index){return Html::a(date('Y:m:d H:i:s',$model->updated_at),'#');}],
                [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="btn btn-primary">查看</span>', Yii::$app->urlManager->createUrl(['/finance/finance-settle-apply/self-fulltime-worker-settle-view', 'FinanceSettleApplySearch[id]' => $model->id],[]), [
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
