<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\FinancePopOrderSearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceRecordLogSearch $searchModel
 */

$this->title = Yii::t('boss', '对账统计');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-record-log-index">



<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 账单查询</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel,'odrinfo'=>$odrinfo]); ?>
    </div>
    </div>
    <p>
        <?php /* echo Html::a(Yii::t('boss', 'Create {modelClass}', [
    'modelClass' => 'Finance Record Log',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
           // 'finance_order_channel_id',
            'finance_order_channel_name',
          //  'finance_pay_channel_id',
            'finance_pay_channel_name',
           'finance_record_log_succeed_count', 
            'finance_record_log_succeed_sum_money', 
            'finance_record_log_manual_count', 
            'finance_record_log_manual_sum_money', 
           'finance_record_log_failure_count', 
            'finance_record_log_failure_money', 
           'finance_record_log_confirm_name', 
//            'finance_record_log_fee',
 
    		[
    		'format' => 'raw',
    		'label' => '预约开始时间',
    		'value' => function ($dataProvider) {
    			return FinancePopOrderSearch::alltime($dataProvider->create_time);
    		},
    		'width' => "100px",
    		],
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-pop-order/billinfo','id' => $model->id,'edit'=>'t']), [
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
            /* 'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 增加', ['create'], ['class' => 'btn btn-success']),  */


            'showFooter'=>false
        ],





    ]); Pjax::end(); ?>

</div>
