<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\AutoOrderSerach $searchModel
 */

$this->title = Yii::t('order', 'AutoOrder');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('order', 'Create {modelClass}', [
    'modelClass' => 'Order',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'order_code',
            'order_parent_id',
            'order_is_parent',
            'created_at',
//            'updated_at', 
//            'order_before_status_dict_id', 
//            'order_before_status_name', 
//            'order_status_dict_id', 
//            'order_status_name', 
//            'order_flag_send', 
//            'order_flag_urgent', 
//            'order_flag_exception', 
//            'order_service_type_id', 
//            'order_service_type_name', 
//            'order_src_id', 
//            'order_src_name', 
//            'channel_id', 
//            'order_channel_name', 
//            'order_channel_order_num', 
//            'customer_id', 
//            'order_ip', 
//            'order_customer_phone', 
//            'order_booked_begin_time', 
//            'order_booked_end_time', 
//            'order_booked_count', 
//            'address_id', 
//            'order_address', 
//            'order_unit_money', 
//            'order_money', 
//            'order_booked_worker_id', 
//            'order_customer_need', 
//            'order_customer_memo', 
//            'order_cs_memo', 
//            'order_pay_type', 
//            'pay_channel_id', 
//            'order_pay_channel_name', 
//            'order_pay_flow_num', 
//            'order_pay_money', 
//            'order_use_acc_balance', 
//            'card_id', 
//            'order_use_card_money', 
//            'coupon_id', 
//            'order_use_coupon_money', 
//            'promotion_id', 
//            'order_use_promotion_money', 
//            'order_lock_status', 
//            'worker_id', 
//            'worker_type_id', 
//            'order_worker_type_name', 
//            'order_worker_send_type', 
//            'shop_id', 
//            'comment_id', 
//            'order_customer_hidden', 
//            'order_pop_pay_money', 
//            'invoice_id', 
//            'checking_id', 
//            'admin_id', 
//            'isdel', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['auto-order/view','id' => $model->id,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
