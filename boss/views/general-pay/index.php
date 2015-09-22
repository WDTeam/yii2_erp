<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', 'General Pays');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-pay-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'General Pay',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'customer_id',
            'order_id',
            'general_pay_money',
            'general_pay_actual_money',
//            'general_pay_source', 
//            'general_pay_source_name', 
//            'general_pay_mode', 
//            'general_pay_status', 
//            'general_pay_transaction_id', 
//            'general_pay_eo_order_id', 
//            'general_pay_memo', 
//            'general_pay_is_coupon', 
//            'admin_id', 
//            'general_pay_admin_name', 
//            'worker_id', 
//            'handle_admin_id', 
//            'general_pay_handle_admin_id', 
//            'general_pay_verify', 
//            'created_at', 
//            'updated_at', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['general-pay/view','id' => $model->id,'edit'=>'t']), [
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
