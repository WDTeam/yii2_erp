<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\FinanceRefund;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceRefundSearch $searchModel
 */

$this->title = Yii::t('app', '确认财务审核');
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
    		'finance_refund_pop_nub',
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
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {tagssign}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-refund/view','id' => $model->id,'edit'=>'t']), [
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
                                                  	return Html::a('<span class="glyphicon glyphicon-tags"></span>', Yii::$app->urlManager->createUrl(['finance-refund/refund','id' => $model->id,'edit'=>'baksite']), $options);
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
             'before'=>Html::a('<i class="glyphicon" ></i>退款总额:1000)', ['countinfo'], ['class' => 'btn btn-info', 'style' => 'margin-right:10px']),
            'showFooter'=>false,
        ],
    ]); Pjax::end(); ?>

</div>
