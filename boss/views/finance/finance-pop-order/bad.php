<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use boss\models\finance\FinancePopOrderSearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinancePopOrderSearch $searchModel
 */

$this->title = Yii::t('app', '坏账列表');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-pop-order-index hideTemp">
      <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-upload"></i> 详情查询</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_searchbad', ['model' => $searchModel,'ordedat' => $ordedatainfo]); ?>
    </div>
    </div>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
    <?php Pjax::begin();
    
     echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
     		
            //'id',
            'order_code',
            'finance_pop_order_number',
           // 'finance_order_channel_id',
            'finance_order_channel_title',
            'finance_pop_order_booked_time:datetime', 
            'finance_pop_order_booked_counttime:datetime', 
     		'order_money',
           'finance_pop_order_sum_money', 
            'finance_pop_order_coupon_count', 
     		'order_status_name',
     		'finance_pop_order_finance_time:datetime',
     		[
     		'format' => 'raw',
     		'label' => '财务审核',
     		'value' => function ($dataProvider) {
     			$platform = FinancePopOrderSearch::is_finance($dataProvider->finance_pop_order_pay_status);
     			return $platform;
     		},
     		'width' => "100px",
     		],
           // 'finance_pop_order_pay_title', 
            'finance_pop_order_msg',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {tagssign} {tagbadyes}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance/finance-pop-order/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);},
'tagssign' => function ($url, $model, $key) {
	$options = [
	'title' => Yii::t('yii', '取消坏账'),
	'aria-label' => Yii::t('yii', '取消坏账'),
	'data-confirm' => Yii::t('kvgrid', '你确定要取消坏账吗?'),
	'data-method' => 'post',
	'data-pjax' => '0'
			];
	return Html::a('<span class="glyphicon glyphicon-backward"></span>', Yii::$app->urlManager->createUrl(['finance/finance-pop-order/tagssign','id' => $model->id,'edit'=>'bak','oid'=>$model->finance_record_log_id]), $options);
},'tagbadyes' => function ($url, $model, $key) {
	$options = [
	'title' => Yii::t('yii', '审核坏账'),
	'aria-label' => Yii::t('yii', '审核坏账'),
	'data-confirm' => Yii::t('kvgrid', '你确定要审核坏账吗?'),
	'data-method' => 'post',
	'data-pjax' => '0'
			];
	return Html::a('<span class="glyphicon glyphicon-info-sign"></span>', Yii::$app->urlManager->createUrl(['finance/finance-pop-order/tagssign','id' => $model->id,'edit'=>'bakinfodabyes','oid'=>$model->finance_record_log_id]), $options);
}
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,


        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).'</h3>',
            'type'=>'info',
            'showFooter'=>false,
        ],
    ]); Pjax::end();

     ?>
