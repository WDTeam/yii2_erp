<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\PaymentLogSearch $searchModel
 */

$this->title = Yii::t('app', '支付记录列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-log-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'payment_log_price',
            'payment_log_shop_name',
            'payment_log_eo_order_id',
            'payment_log_transaction_id',
//            'payment_log_status',
//            'pay_channel_id', 
//            'payment_log_json_aggregation:ntext',
//            'created_at', 
//            'updated_at', 


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['payment-log/view', 'id' => $model->id, 'edit' => 't']), [
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


        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'showFooter' => false
        ],
    ]);
    Pjax::end(); ?>

</div>
