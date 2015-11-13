<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\tabs\TabsX;
use core\models\Customer\Customer;
use boss\models\finance\FinancePopOrderSearch;
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
     'action' => ['generalmepost'],
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
     			return $dataProvider->order_id;
     		}
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '交易金额',
     		'value' => function ($dataProvider) {
     			return $dataProvider->general_pay_money;
     		}
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '用户电话',
     		'value' => function ($dataProvider) {
     		$userinfo=Customer::getCustomerById($dataProvider->customer_id);
     		if($userinfo){
     		return  $userinfo->customer_phone;
     		}else {
     		return  '<font color="red">暂无</font>';
     		}
   		   
     		}
     		],
     		[
     		'format' => 'raw',
     		'label' => '交易方式',
     		'value' => function ($dataProvider) {
     			$userinfo_order_stype=FinancePopOrderSearch::selsect_isstatus($dataProvider->general_pay_mode);
     			return  $userinfo_order_stype;
     		}
     		],
     		[
     		'format' => 'raw',
     		'label' => '数据来源',
     		'value' => function ($dataProvider) {
     			return $dataProvider->general_pay_source_name;
     		}
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '充值状态',
     		'value' => function ($dataProvider) {
     		if($dataProvider->general_pay_status==1){
     		$status='成功';
     		}else{
     		$status='失败';
     		}	
     		return $status;
     		
     		}
     		],
     		[
     		'format' => 'raw',
     		'label' => '管理员',
     		'value' => function ($dataProvider) {
     			return $dataProvider->general_pay_admin_name?$dataProvider->general_pay_admin_name:'未处理';
     		}
     		],
     		[
     		'format' => 'raw',
     		'label' => '时间',
     		'value' => function ($dataProvider) {
     		return date('Y-m-d H:i', $dataProvider->created_at);
     		}
     		],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance/finance-pop-order/view','id' => $model->id,'edit'=>'t']), [
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
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
            'type'=>'info',
           'before'=>
           Html::submitButton(Yii::t('app', '批量 '), ['class' => 'btn btn-default','style' => 'margin-right:10px']),
			/* 'after' => Html::a('批量审核',
			['index'],
			['class' => 'btn btn-default']), */
            'showFooter'=>false,
        ],
    ]);

   
$items = [
[
'label'=>'<i class="glyphicon glyphicon-list-alt"></i> 订单对账',
'content'=>'',
'active'=>false,
'url' => ['finance/finance-pop-order/orderlist?id='.$id]
],
[
'label'=>'<i class="glyphicon glyphicon-king"></i> 充值对账',
'content'=>$paychannel,
'active'=>true,
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
