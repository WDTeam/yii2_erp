<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use boss\models\FinancePopOrderSearch;
use yii\widgets\ActiveForm;
use kartik\tabs\TabsX;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinancePopOrderSearch $searchModel
 */

$this->title = Yii::t('app', '我有三没有订单对账');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="hideTemp">
<?php 
     ActiveForm::begin([
     'action' => ['indexmepost'],
     'method' => 'post'
     		]);
   $paychannel= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
     		'class' => 'yii\grid\CheckboxColumn',
     		'name'=>'ids'
],
     		[
     		'format' => 'raw',
     		'label' => '订单号',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_code;
     		},
     		'width' => "100px",
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '订单渠道',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_channel_name;
     		},
     		'width' => "80px",
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '用户电话',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_customer_phone;
     		},
     		'width' => "80px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '预约开始时间',
     		'value' => function ($dataProvider) {
     			return FinancePopOrderSearch::alltime($dataProvider->order_booked_begin_time);
     		},
     		'width' => "150px",
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '订单金额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_money;
     		},
     		'width' => "80px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '实际支付金额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_pay_money;
     		},
     		'width' => "85px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '使用余额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_use_acc_balance;
     		},
     		'width' => "85px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '使用服务卡金额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_use_card_money;
     		},
     		'width' => "85px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '使用优惠卷金额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_use_coupon_money;
     		},
     		'width' => "85px",
     		], 
     		[
     		'format' => 'raw',
     		'label' => '使用促销金额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_use_promotion_money;
     		},
     		'width' => "85px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '使用余额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_use_acc_balance;
     		},
     		'width' => "85px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '使用服务卡金额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_use_coupon_money;
     		},
     		'width' => "100px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '使用促销金额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_use_promotion_money;
     		},
     		'width' => "100px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '有无子订单',
     		'value' => function ($dataProvider) {
     		 if($dataProvider->order_is_parent==1){
     		 	return '有';
     		 }else{
     		 	return '无';
     		 }
     		},
     		'width' => "100px",
     		],
     		[
     		'format' => 'raw',
     		'label' => '订单状态',
     		'value' => function ($dataProvider) {
     			return $dataProvider->order_status_name;
     		},
     		'width' => "100px",
     		],
     		'order_cs_memo',
           /*  [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-pop-order/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ], */
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,


        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
            'type'=>'info',
           'before'=>
              /* Html::submitButton(Yii::t('app', '批量 '), ['class' => 'btn btn-default','style' => 'margin-right:10px']) */'',
			/* 'after' => Html::a('批量审核',
			['index'],
			['class' => 'btn btn-default']), */ 
            'showFooter'=>false,
        ],
    ]);

   
$items = [
[
'label'=>'<i class="glyphicon glyphicon-list-alt"></i> 订单对账',
'content'=>$paychannel,
'active'=>true,
],
[
'label'=>'<i class="glyphicon glyphicon-king"></i> 充值对账',
'content'=>'',
'active'=>false,
'url' => ['finance-pop-order/generalpaylist']
]
];
    echo TabsX::widget([
    		'items'=>$items,
    		'position'=>TabsX::POS_ABOVE,
    		'bordered'=>true,
    		'encodeLabels'=>false
    		]);
    ActiveForm::end();?> 

</div>
</div>
