<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use boss\models\FinancePopOrderSearch;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinancePopOrderSearch $searchModel
 */

$this->title = Yii::t('app', '对账详情');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-pop-order-index hideTemp">
      <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-upload"></i> 详情查询</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel,'ordedat' => $ordedatainfo]); ?>
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
     		
           // 'id',
            'finance_pop_order_number',
           // 'finance_order_channel_id',
            'finance_order_channel_title',
          //  'finance_pay_channel_id',
            //'finance_pay_channel_title', 
            'finance_pop_order_customer_tel', 
      //      'finance_pop_order_worker_uid', 
            'finance_pop_order_booked_time:datetime', 
            'finance_pop_order_booked_counttime:datetime', 
           'finance_pop_order_sum_money', 
            'finance_pop_order_coupon_count', 
//            'finance_pop_order_coupon_id', 
//            'finance_pop_order_order2', 
         //  'finance_pop_order_channel_order', 
//            'finance_pop_order_order_type', 
//            'finance_pop_order_status', 
          // 'finance_pop_order_finance_isok', 
//            'finance_pop_order_discount_pay', 
//            'finance_pop_order_reality_pay', 
//            'finance_pop_order_order_time:datetime', 
//            'finance_pop_order_pay_time:datetime', 

     		[
     		'format' => 'raw',
     		'label' => '财务审核',
     		'value' => function ($dataProvider) {
     			$platform = FinancePopOrderSearch::is_finance($dataProvider->finance_pop_order_pay_status);
     			return $platform;
     		},
     		'width' => "100px",
     		],
            'finance_pop_order_pay_title', 
//            'finance_pop_order_check_id', 
//            'finance_pop_order_finance_time:datetime', 
//            'create_time:datetime', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-pop-order/view','id' => $model->id,'edit'=>'t']), [
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
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).'</h3>',
            'type'=>'info',
            'showFooter'=>false,
        ],
    ]); Pjax::end();

     ?>

</div>
