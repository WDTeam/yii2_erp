<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceCompensateSearch $searchModel
 */

$this->title = Yii::t('app', 'Finance Compensates');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-compensate-index hideTemp">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Finance Compensate',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'finance_compensate_oa_num',
            'finance_compensate_pay_money',
            'finance_compensate_cause',
            'finance_compensate_tel',
//            'finance_compensate_money', 
//            'finance_pay_channel_id', 
//            'finance_pay_channel_name', 
//            'finance_order_channel_id', 
//            'finance_order_channel_name', 
//            'finance_compensate_discount', 
//            'finance_compensate_pay_create_time:datetime', 
//            'finance_compensate_pay_flow_num', 
//            'finance_compensate_pay_status', 
//            'finance_compensate_worker_id', 
//            'finance_compensate_worker_tel', 
//            'finance_compensate_proposer', 
//            'finance_compensate_auditor', 
//            'create_time:datetime', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-compensate/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
