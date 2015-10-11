<?php

use yii\helpers\Html;
use kartik\grid\GridView;
/* use yii\widgets\Pjax;
use kartik\nav\NavX;
use yii\bootstrap\NavBar; */
use boss\models\FinancePopOrderSearch;
use yii\widgets\ActiveForm;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinancePopOrderSearch $searchModel
 */

$this->title = Yii::t('app', '对账管理');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="finance-pop-order-index hideTemp">
      <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-upload"></i> 上传对账单</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel,'ordedat' => $ordedatainfo]); ?>
    </div>
    </div>
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>
</div>
    <?php
   
    ActiveForm::begin([
    'action' => ['indexall'],
    'method' => 'post'
    		]);
  

     echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            [
     		'class' => 'yii\grid\CheckboxColumn',
     		'name'=>'ids'

],
     		
         //   'id',
            'finance_pop_order_number',
           // 'finance_order_channel_id',
     		[
     		'format' => 'raw',
     		'label' => '渠道名称',
     		'value' => function ($dataProvider) {
     			return $dataProvider->finance_order_channel_title ? $dataProvider->finance_order_channel_title  : '未知';
     		},
     		'width' => "100px",
     		],
            //'finance_pay_channel_id',
          //  'finance_pay_channel_title', 
            //'finance_pop_order_customer_tel', 
            [
            'format' => 'raw',
            'label' => '阿姨姓名',
            'value' => function ($dataProvider) {
            	return $dataProvider->finance_pop_order_worker_uid ? $dataProvider->finance_pop_order_worker_uid  : '未知';
            },
            'width' => "100px",
            ],
            [
            'format' => 'raw',
            'label' => '预约开始时间',
            'value' => function ($dataProvider) {
            	return FinancePopOrderSearch::alltime($dataProvider->finance_pop_order_booked_time);
            },
            'width' => "100px",
            ],
            [
            'format' => 'raw',
            'label' => '预约服务时长',
            'value' => function ($dataProvider) {
            	return FinancePopOrderSearch::alltimecount($dataProvider->finance_pop_order_booked_counttime);
            },
            'width' => "100px",
            ],
            'finance_pop_order_sum_money', 
           'finance_pop_order_coupon_count', 
//            'finance_pop_order_coupon_id', 
//            'finance_pop_order_order2', 
//            'finance_pop_order_channel_order', 
//            'finance_pop_order_order_type', 
//            'finance_pop_order_status', 
//            'finance_pop_order_finance_isok', 
           'finance_pop_order_discount_pay', 
           'finance_pop_order_reality_pay', 
//            'finance_pop_order_order_time:datetime', 
//            'finance_pop_order_pay_time:datetime', 
//            'finance_pop_order_pay_status', 
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
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
            'type'=>'info',
           'before'=>
           Html::submitButton(Yii::t('app', '批量 '), ['class' => 'btn btn-default','style' => 'margin-right:10px']).
           Html::a('<i class="glyphicon" ></i>对账成功(总额:'.$searchModel->OrderPayStatus(1,$lastidRecordLogid).')', ['index?FinancePopOrderSearch[finance_pop_order_pay_status_type]=1&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(1,$statusdeflde).'', 'style' => 'margin-right:10px']) .
Html::a('<i class="glyphicon" ></i>我有你没 (总额:'.$searchModel->OrderPayStatus(3,$lastidRecordLogid).')', ['index?FinancePopOrderSearch[finance_pop_order_pay_status_type]=3&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(3,$statusdeflde).'', 'style' => 'margin-right:10px']) .
Html::a('<i class="glyphicon" ></i>你有我没 (总额:'.$searchModel->OrderPayStatus(2,$lastidRecordLogid).')', ['index?FinancePopOrderSearch[finance_pop_order_pay_status_type]=2&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(2,$statusdeflde).'', 'style' => 'margin-right:10px']) .
Html::a('<i class="glyphicon" ></i>金额不对 (总额:'.$searchModel->OrderPayStatus(4,$lastidRecordLogid).')', ['index?FinancePopOrderSearch[finance_pop_order_pay_status_type]=4&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(4,$statusdeflde).'', 'style' => 'margin-right:10px']) .
Html::a('<i class="glyphicon" ></i>状态不对(总额:'.$searchModel->OrderPayStatus(5,$lastidRecordLogid).')', ['index?FinancePopOrderSearch[finance_pop_order_pay_status_type]=5&id='.$lastidRecordLogid], ['class' => 'btn btn-'.$searchModel->defaultcss(5,$statusdeflde).'', 'style' => 'margin-right:10px']),
			/* 'after' => Html::a('批量审核',
			['index'],
			['class' => 'btn btn-default']), */
            'showFooter'=>false,
        ],
    ]);
       ActiveForm::end();

     ?>

</div>
