<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\operation\OperationServiceCardWithCustomerSearch $searchModel
 */

$this->title = Yii::t('app', '服务卡客户关系');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-with-customer-index">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Service Card With Customer',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
		'toolbar' =>[
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'service_card_sell_record_id',
            'service_card_sell_record_code',
            'service_card_info_id',
            'service_card_with_customer_code',
            'service_card_info_name', 
            'customer_trans_record_pay_money', 
//            'service_card_info_value', 
//            'service_card_info_rebate_value', 
//            'service_card_with_customer_balance', 
            'customer_id', 
//            'customer_phone', 
//            'service_card_info_scope', 
//            'service_card_with_customer_buy_at', 
//            'service_card_with_customer_valid_at', 
//            'service_card_with_customer_activated_at', 
//            'service_card_with_customer_status', 
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
#            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
