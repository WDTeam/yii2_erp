<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use dbbase\models\finance\FinanceRefund;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceRefundSearch $searchModel
 */

$this->title = Yii::t('app', '财务审核确认');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="finance-refund-index hideTemp">
 <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-upload"></i>退款查询</h3>
    </div>
 	<div class="panel-body">
    <?php  echo $this->render('_search', ['model' => $searchModel,'ordedat'=>$ordedat]); ?>
    </div>
    </div>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
    		
    		'finance_refund_pay_flow_num',
    		[
    		'format' => 'raw',
    		'label' => '退款金额',
    		'value' => function ($dataProvider) {
    			$sum=($dataProvider->finance_refund_money)+($dataProvider->order_use_acc_balance)+($dataProvider->order_use_card_money)+($dataProvider->finance_refund_discount);
    			return $sum;
    		},
    		'width' => "80px",
    		],
    		[
    		'format' => 'raw',
    		'label' => '订单金额',
    		'value' => function ($dataProvider) {
    			return $dataProvider->order_money;
    		},
    		'width' => "80px",
    		],
    		'finance_refund_discount',//优惠价order_use_coupon_money
    		
    		[
    		'format' => 'raw',
    		'label' => '实收金额',
    		'value' => function ($dataProvider) {
    			$sum=($dataProvider->finance_refund_money)+($dataProvider->order_use_acc_balance)+($dataProvider->order_use_card_money);
    			return $sum;
    		},
    		'width' => "80px",
    		],
    		
    		'order_use_acc_balance',
    		'order_use_card_money',
    		'order_use_promotion_money',
    		
    		
    		'finance_order_channel_title',
    		'finance_pay_channel_title',
    		[
    		'format' => 'raw',
    		'label' => '收款状态',
    		'value' => function ($dataProvider) {
    			return $dataProvider->finance_refund_pay_status==1 ?'支付':'未支付';
    		},
    		'width' => "80px",
    		],
    		'finance_refund_code',
    		'finance_refund_reason',
    		'finance_refund_tel',
    		[
			'format' => 'raw',
			'label' => '状态',
			'value' => function ($dataProvider) {
				return FinanceRefund::get_status($dataProvider->isstatus);
			},
			'width' => "60px",
			],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {tagssign}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="btn btn-primary">修改</span>', Yii::$app->urlManager->createUrl(['/finance/finance-refund/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', '修改'),
                                                  ]);},
                                                  'tagssign' => function ($url, $model, $key) {
                                                  	$options = [
                                                  	'title' => Yii::t('yii', '退款'),
                                                  	'aria-label' => Yii::t('yii', '退款'),
                                                  	'data-confirm' => Yii::t('kvgrid', '你确定退款?'),
                                                  	'data-method' => 'post',
                                                  	'data-pjax' => '0'
                                                  			];
                                                  	return Html::a('<span class="glyphicon glyphicon-tags"></span>', Yii::$app->urlManager->createUrl(['finance/finance-refund/refund','id' => $model->id,'edit'=>'baksite']), $options);
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
            'showFooter'=>false,
        ],
    ]); Pjax::end(); ?>

</div>
