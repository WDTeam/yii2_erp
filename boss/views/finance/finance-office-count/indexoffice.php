<style>
    .kv-grid-table tr td {background-color: #fff;}
</style>
<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;


/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinancePopOrderSearch $searchModel
 */

$this->title = Yii::t('app', '日派单管理');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="finance-pop-order-index hideTemp">
      <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-upload"></i> 日派单查询</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_searchoffice', ['model' => $searchModel]); ?>
    </div>
    </div>
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
     		'class' => 'yii\grid\SerialColumn',
			],
     		
     		[
     		'format' => 'raw',
     		'label' => '订单号',
     		'value' =>function ($dataProvider) { return $dataProvider->order_code; },
     		'options'=>[ 'style'=>'background:orange'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '计划时长',
     		'value' =>function ($dataProvider) { return $dataProvider->order_booked_count; },
     		'options'=>[ 'style'=>'background:orange'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '实际时长',
     		'value' =>function ($dataProvider) { return $dataProvider->order_booked_end_time; },
     		'options'=>[ 'style'=>'background:orange'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '订单单价',
     		'value' =>function ($dataProvider) { return $dataProvider->order_unit_money; },
     		'options'=>[ 'style'=>'background:orange'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '订单金额',
     		'value' =>function ($dataProvider) { return $dataProvider->order_money; },
     		'options'=>[ 'style'=>'background:orange'],
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '订单渠道',
     		'value' =>function ($dataProvider) { return $dataProvider->order_channel_name; },
     		'options'=>[ 'style'=>'background:pink'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '支付渠道',
     		'value' =>function ($dataProvider) { return $dataProvider->order_pay_channel_name; },
     		'options'=>[ 'style'=>'background:pink'],
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '渠道营销费',
     		'value' =>function ($dataProvider) { return $dataProvider->order_pop_operation_money; },
     		'options'=>[ 'style'=>'background:pink'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '手续费',
     		'value' =>function ($dataProvider) { return $dataProvider->order_pop_operation_money; },
     		'options'=>[ 'style'=>'background:pink'],
     		],
     		
     		
     		
     		[
     		'format' => 'raw',
     		'label' => '订单渠道',
     		'value' =>function ($dataProvider) { return $dataProvider->order_channel_name; },
     		'options'=>[ 'style'=>'background:brown'],
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '支付渠道',
     		'value' =>function ($dataProvider) { return $dataProvider->order_pay_channel_name; },
     		'options'=>[ 'style'=>'background:brown'],
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '渠道营销费',
     		'value' =>function ($dataProvider) { return $dataProvider->order_pop_operation_money; },
     		'options'=>[ 'style'=>'background:brown'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '抽成',
     		'value' =>function ($dataProvider) { return $dataProvider->order_pop_operation_money; },
     		'options'=>[ 'style'=>'background:brown'],
     		],

     		[
     		'format' => 'raw',
     		'label' => '对账状态',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:palegreen'],
			],
     		[
     		'format' => 'raw',
     		'label' => '应收款数',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:palegreen'],
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '收款状态',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:palegreen'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '提款渠道',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:palegreen'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '提款金额',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:palegreen'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '结余',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:palegreen'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '客户订单应收金额 ',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:red'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '代收代付阿姨结算金额',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:seagreen'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '公司抽佣',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:seagreen'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '代收代付退款金额',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:seagreen'],
     		],
     		
     		[
     		'format' => 'raw',
     		'label' => '代收代付余额',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:seagreen'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '公司收款结算',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:rosybrown'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '公司结算退款',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:rosybrown'],
     		],
     		[
     		'format' => 'raw',
     		'label' => '公司结算余额',
     		'value' =>function ($dataProvider) { return '--'; },
     		'options'=>[ 'style'=>'background:rosybrown'],
     		],
     		
     		[
     		'attribute'=>'--',
     		'width'=>'80px',
     		'value'=>function ($model, $key, $index, $widget) {
     			return '合计';
     		},
     		
     		'filterWidgetOptions'=>[
     		'pluginOptions'=>['allowClear'=>false],
     		],
     		'filterInputOptions'=>['placeholder'=>'Any supplier'],
     				'group'=>true,
     				'groupFooter'=>function ($model, $key, $index, $widget) {
     				return [
     				'mergeColumns'=>[[1,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27]],
     					'content'=>[
     					1=>'合计',
     					2=>GridView::F_SUM,
     					3=>GridView::F_SUM,
						5=>GridView::F_SUM,
						8=>GridView::F_SUM,
						9=>GridView::F_SUM,
						12=>GridView::F_SUM,
						13=>GridView::F_SUM,
						15=>GridView::F_SUM,
						18=>GridView::F_SUM,
						19=>GridView::F_SUM,
						20=>GridView::F_SUM,
						21=>GridView::F_SUM,
						22=>GridView::F_SUM,
						23=>GridView::F_SUM,
						24=>GridView::F_SUM,
						25=>GridView::F_SUM,
						26=>GridView::F_SUM,
						27=>GridView::F_SUM,
     							],
     							'options'=>['class'=>'danger','style'=>'font-weight:bold;']
     							];
     				}
     				],
     				
     				
     				
     				
     		
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '. Html::encode($this->title) . ' </h3>',
            'type'=>'info',
            'showFooter'=>false,
        ],
    ]);
       ActiveForm::end();
  

?>
