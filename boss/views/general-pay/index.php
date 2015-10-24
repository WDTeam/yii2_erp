<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\pay\GeneralPay;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', '支付列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-pay-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' =>
        [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'customer_id',
            'order_id',
            'general_pay_money',
            'general_pay_actual_money',
            //'general_pay_source',
            'general_pay_source_name',
            [
                'attribute' => 'general_pay_mode',
                'options'=>['width'=>80,],
                "value" => function($model){
                    return GeneralPay::$PAY_MODE[$model->general_pay_mode];
                }
            ],
            [
                'attribute' => 'general_pay_status',
                'options'=>['width'=>50,],
                "value" => function($model){
                    return GeneralPay::$PAY_STATUS[$model->general_pay_status];
                }
            ],
            'general_pay_transaction_id',
            'general_pay_eo_order_id',
            //'general_pay_memo',
            //'general_pay_is_coupon',
            //'admin_id',
            //'general_pay_admin_name',
            //'worker_id',
            //'handle_admin_id',
            //'general_pay_handle_admin_id',
            //'general_pay_verify',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'Y-m-d H:i:s'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model)
                    {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',Yii::$app->urlManager->createUrl(['general-pay/view','id' => $model->id,'edit'=>'t']),
                            ['title' => Yii::t('yii', 'Edit'),]);
                    }
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
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
