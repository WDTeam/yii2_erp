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

$this->title = Yii::t('app', '退款统计');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="finance-refund-index hideTemp">
 <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-upload"></i>统计查询</h3>
    </div>
 	<div class="panel-body">
    <?php  echo $this->render('_searchcount', ['model' => $searchModel]); ?>
    </div>
    </div>
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
    	//'showPageSummary'=>true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'finance_refund_tel',
    		'create_time:datetime',
    		'finance_refund_money',
    		[
    		'format' => 'raw',
    		'label' => '申请方式',
    		'value' => function ($dataProvider) {
    			return $dataProvider->finance_refund_stype==1 ?'用户取消':'未知';
    		},
    		'width' => "80px",
    		],
    		'finance_refund_reason',
    		'finance_refund_discount',
    		'finance_refund_pay_create_time:datetime',
    		'finance_pay_channel_title',
    		
    		
    		[
    		'format' => 'raw',
    		'label' => '流水号',
    		'value' => function ($dataProvider) {
    			return $dataProvider->finance_refund_code;
    		},
    		'width' => "80px",
    		],
    		
    		//'finance_refund_pop_nub',
    		[
    		'format' => 'raw',
    		'label' => '支付状态',
    		'value' => function ($dataProvider) {
    			return $dataProvider->finance_refund_stype==1 ?'已支付':'未支付';
    		},
    		'width' => "80px",
    		],
    		[
    		'format' => 'raw',
    		'label' => '服务阿姨',
    		'value' => function ($dataProvider) {
    			return FinanceRefund::get_adminname($dataProvider->finance_refund_worker_id);
    		},
    		'width' => "80px",
    		],
    		
    		'finance_refund_worker_tel',
			[
			'format' => 'raw',
			'label' => '状态',
			'value' => function ($dataProvider) {
				return FinanceRefund::get_status($dataProvider->isstatus);
			},
			'width' => "60px",
			],
            /* [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance/finance-refund/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', '修改'),
                                                  ]);}
                                                  

                ],
            ], */
          
			[
			'attribute'=>'refund_money',
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
            						'mergeColumns'=>[[1,1,3,4,5,6]],
     						'content'=>[
										1=>'合计',
										3=>GridView::F_SUM,
										6=>GridView::F_SUM
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
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
			'before' =>$this->render('_query_links', ['model' => $searchModel]),
            'showFooter'=>true
        ],




    ]);

    Pjax::end(); ?>

</div>
