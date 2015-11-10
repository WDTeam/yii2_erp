<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', '用户交易记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-trans-record-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'payment_customer_trans_record_mode_name',
                'label'=>'交易方式',
            ],
            [
                'attribute' => 'payment_customer_trans_record_transaction_id',
                'label'=>'交易流水号',
            ],
            [
                'attribute' => 'payment_customer_trans_record_eo_order_id',
                'label'=>'商户订单号',
            ],
            [
                'attribute' => 'customer_id',
                'label'=>'用户ID',
            ],
            [
                'attribute' => 'order_id',
                'label'=>'E家洁订单号',
            ],
            [
                'attribute' => 'payment_customer_trans_record_order_channel',
                'label'=>'订单渠道',
            ],
            [
                'attribute' => 'payment_customer_trans_record_pay_channel',
                'label'=>'支付渠道',
            ],
            [
                'attribute' => 'payment_customer_trans_record_order_total_money',
                'label'=>'A:订单总金额',
                'options'=>[ 'style'=>'background:#FFFFCC'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_coupon_id',
                'label'=>'B:优惠券ID',
                'options'=>[ 'style'=>'background:#CCFFFF'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_coupon_code',
                'label'=>'B1:优惠券编码',
                'options'=>[ 'style'=>'background:#CCFFFF'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_coupon_money',
                'label'=>'B2:优惠券金额',
                'options'=>[ 'style'=>'background:#CCFFFF'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_coupon_transaction_id',
                'label'=>'B3:优惠券流水号',
                'options'=>[ 'style'=>'background:#CCFFFF'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_cash',
                'label'=>'C:现金支付',
                'options'=>[ 'style'=>'background:#FFCCCC'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_pre_pay',
                'label'=>'D:预付费金额(第三方)',
                'options'=>[ 'style'=>'background:#99CCCC'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_online_pay',
                'label'=>'E:在线支付',
                'options'=>[ 'style'=>'background:#FFCC99'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_online_balance_pay',
                'label'=>'F:用户余额支付',
                'options'=>[ 'style'=>'background:#FFCCCC'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_befor_balance',
                'label'=>'F1:支付前用户余额',
                'options'=>[ 'style'=>'background:#FFCCCC'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_current_balance',
                'label'=>'F2:支付后用户余额',
                'options'=>[ 'style'=>'background:#FFCCCC'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_balance_transaction_id',
                'label'=>'F3:余额流水号',
                'options'=>[ 'style'=>'background:#FFCCCC'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_service_card_pay',
                'label'=>'G:服务卡支付',
                'options'=>[ 'style'=>'background:#FF9999'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_service_card_on',
                'label'=>'G1:服务卡卡号',
                'options'=>[ 'style'=>'background:#FF9999'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_service_card_current_balance',
                'label'=>'G2:支付前服务卡余额',
                'options'=>[ 'style'=>'background:#FF9999'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_service_card_befor_balance',
                'label'=>'G3:支付后服务卡余额',
                'options'=>[ 'style'=>'background:#FF9999'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_service_card_transaction_id',
                'label'=>'G4:服务卡流水号',
                'options'=>[ 'style'=>'background:#FF9999'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_compensate_money',
                'label'=>'H:补偿金额',
                'options'=>[ 'style'=>'background:#996699'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_refund_money',
                'label'=>'I:退款金额',
                'options'=>[ 'style'=>'background:#FFCCCC'],
            ],
            [
                'attribute' => 'payment_customer_trans_record_total_money',
                'label'=>'J:用户累计交易额',
                'options'=>[ 'style'=>'background:#FF6666'],
            ],

            //'payment_customer_trans_record_remark',
            //'payment_customer_trans_record_verify',
            //'created_at',
            //'updated_at',
            //'is_del',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['customer-trans-record/view', 'id' => $model->id, 'edit' => 't']), [
                            'title' => Yii::t('yii', 'Edit'),
                        ]);
                    }

                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'toolbar' => '',
        'rowOptions' =>function ($model, $key, $index, $grid){
            if($model->payment_customer_trans_record_verify != $model::sign($model->getAttributes()))
            {
                return ['class'=>'text-red','t1'=>$model->payment_customer_trans_record_verify,'t2'=>$model::sign($model->getAttributes())];
            }
        },
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'showFooter' => false
        ],
    ]);
    Pjax::end(); ?>

</div>
