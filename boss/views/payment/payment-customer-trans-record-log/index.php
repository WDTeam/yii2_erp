<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', 'Customer Trans Record Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-customer-trans-record-log-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Customer Trans Record Log',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'customer_id',
            'order_id',
            'order_channel_id',
            'payment_customer_trans_record_order_channel',
//            'pay_channel_id', 
//            'payment_customer_trans_record_pay_channel', 
//            'payment_customer_trans_record_mode', 
//            'payment_customer_trans_record_mode_name', 
//            'payment_customer_trans_record_promo_code_money', 
//            'payment_customer_trans_record_coupon_money', 
//            'payment_customer_trans_record_cash', 
//            'payment_customer_trans_record_pre_pay', 
//            'payment_customer_trans_record_online_pay', 
//            'payment_customer_trans_record_online_balance_pay', 
//            'payment_customer_trans_record_service_card_on',
//            'payment_customer_trans_record_service_card_pay',
//            'payment_customer_trans_record_service_card_current_balance',
//            'payment_customer_trans_record_service_card_befor_balance',
//            'payment_customer_trans_record_compensate_money', 
//            'payment_customer_trans_record_refund_money', 
//            'payment_customer_trans_record_money', 
//            'payment_customer_trans_record_order_total_money', 
//            'payment_customer_trans_record_total_money', 
//            'payment_customer_trans_record_current_balance', 
//            'payment_customer_trans_record_befor_balance', 
//            'payment_customer_trans_record_transaction_id', 
//            'payment_customer_trans_record_remark', 
//            'payment_customer_trans_record_verify', 
//            'created_at', 
//            'updated_at', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['customer-trans-record-log/view','id' => $model->id,'edit'=>'t']), [
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
