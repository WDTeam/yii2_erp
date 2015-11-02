<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\operation\OperationServiceCardConsumeRecordSearch $searchModel
 */

$this->title = Yii::t('app', '服务卡消费记录');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-consume-record-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Service Card Consume Record',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
		'toolbar' =>[],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'customer_id',
            'customer_trans_record_transaction_id',
            'order_id',
            'order_code',
//            'service_card_with_customer _id', 
//            'service_card_with_customer_code', 
            'service_card_consume_record_front_money', 
            'service_card_consume_record_behind_money', 
//            'service_card_consume_record_consume_type', 
//            'service_card_consume_record_business_type', 
            'service_card_consume_record_use_money', 
//            'created_at', 
//            'updated_at', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{view}',
                'buttons' => [
					'view' => function ($url, $model) {
						return Html::a(Yii::t('yii', '查看'), ['view', 'id' => $model->id], [
							'title' => Yii::t('yii', '查看'),
							'class' => 'btn btn-success btn-sm'
						]);
					},		
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
//            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
