<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', '支付列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' =>
        [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'customer_id',
            'order_id',
            'payment_money',
            'payment_actual_money',
            'payment_source_name',
            [
                'attribute' => 'payment_mode',
                'options'=>['width'=>80,],
                "value" => function($model){
                    return $model::$PAY_MODE[$model->payment_mode];
                }
            ],
            [
                'attribute' => 'payment_status',
                'options'=>['width'=>50,],
                "value" => function($model){
                    return $model::$PAY_STATUS[$model->payment_status];
                }
            ],
            'payment_transaction_id',
            'payment_eo_order_id',
            //'payment_memo',
            //'payment_type',
            //'admin_id',
            //'payment_admin_name',
            //'worker_id',
            //'handle_admin_id',
            //'payment_handle_admin_id',
            //'payment_verify',
            [
                'attribute' => 'created_at',
                'value'=>function($model){
                    return date("Y-m-d H:i:s",$model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value'=>function($model){
                    return date("Y-m-d H:i:s",$model->updated_at);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'update' => function ($url, $model)
                    {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',Yii::$app->urlManager->createUrl(['payment/view','id' => $model->id,'edit'=>'t']),
                            ['title' => Yii::t('yii', 'Edit'),]);
                    }
                ],
            ],

        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'toolbar' => '',
        'rowOptions' =>function ($model, $key, $index, $grid){
            if($model->payment_verify != $model->sign())
            {
                return ['class'=>'text-red','verify'=>$model->payment_verify,'sign'=>$model->sign()];
            }
        },
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
