<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use core\models\finance\FinanceShopSettleApplySearch;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '门店阿姨结算订单列表');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div id = "manualSettle" class="panel panel-info">
        <div class="panel-heading">
            <label class="panel-title">门店阿姨结算订单列表</label>
        </div>
        <div>
            
             <?php 
           echo '<div id = "allOrderInfo" >';
                Pjax::begin(); echo GridView::widget([
               'financeWorkerOrderIncomeDataProvider' => $financeWorkerOrderIncomeDataProvider,
               'columns' => [
                   ['class' => 'yii\grid\SerialColumn'],
                   ['attribute'=>'order_code',
                       'header' => Yii::t('app', '订单号'),
                        'content'=>function($model,$key,$index)
                               {return  Html::a('<u>'.$model['order_code'].'</u>',[Yii::$app->urlManager->createUrl(['order/order/edit/','id' => $model['order_code']])],['data-pjax'=>'0','target' => '_blank',]);}
                    ],
                    ['attribute'=>'order_service_type_name',
                       'header' => Yii::t('app', '服务类型'),],
                    ['attribute'=>'order_channel_name',
                       'header' => Yii::t('app', '渠道'),],
                    [
                       'header' => Yii::t('app', '支付方式'),
                        'attribute' => 'order_pay_type_des',
                    ],
                    ['attribute'=>'order_booked_begin_time',
                       'header' => Yii::t('app', '服务开始时间'),
                        'content'=>function($model,$key,$index){
                                   return date('Y-m-d h:i:s',$model['order_booked_begin_time']);
                       },
                    ], 
                    ['attribute'=>'order_booked_count',
                       'header' => Yii::t('app', '服务工时（小时）'),
                        'content'=>function($model,$key,$index){
                                   return $model['order_booked_count'];
                       },
                    ], 
                    ['attribute'=>'order_unit_money',
                       'header' => Yii::t('app', '费率（元/小时）'),
                    ], 
                    ['attribute'=>'order_money',
                       'header' => Yii::t('app', '订单总金额（元）'),
                    ], 
                    [
                       'header' => Yii::t('app', '优惠金额（元）'),
                        'content'=>function($model,$key,$index){
                                   return 0;
                       },
                    ],
                    [
                       'attribute'=>'finance_worker_order_income_discount_amount',
                       'header' => Yii::t('app', '用户支付金额（元）'),
                    ],
                    [
                       'attribute'=>'order_pay_money',
                       'header' => Yii::t('app', '阿姨结算金额（元）'),
                    ],
               ],
               'responsive'=>true,
               'hover'=>true,
               'condensed'=>true,
               'floatHeader'=>true,
           'panel' => [
             'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> 所有订单明细 </h3>',
            ],
           ]); Pjax::end(); 
           echo '</div>';
            ?>
        </div>
    </div>
</div>

