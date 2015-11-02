<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\operation\OperationServiceCardInfoSearch $searchModel
 */

$this->title = Yii::t('app', '服务卡信息');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-service-card-info-index">
    <?php  echo $this->render('_search', ['model' => $searchModel,'config'=>$config,]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Service Card Info',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
		'toolbar' =>[
            'content'=>Html::a('<i class="glyphicon glyphicon-plus"></i>', [
            'create'
            ], [
                'class' => 'btn btn-default',
                'title' => Yii::t('app', '添加新服务卡')
            ]),
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'service_card_info_name',
//            'service_card_info_card_type',
//            'service_card_info_card_level',
			[
                'attribute'=>'service_card_info_card_type',
                'value'=>function($model){
                     return $model->getServiceCardConfig()['type'][$model->service_card_info_card_type];
                },
                'filter'=>false,
            ],
            [
                'attribute'=>'service_card_info_card_level',
				'format'=>'raw',
                'value'=>function($model){
                    return $model->getServiceCardConfig()['level'][$model->service_card_info_card_level];
                },
                'filter'=>false,
            ],
            'service_card_info_value',
            'service_card_info_rebate_value', 
            'service_card_info_use_scope', 
            'service_card_info_valid_days', 
//            'created_at', 
//            'updated_at', 
			[
                'attribute'=>'created_at',
                'value'=>function($model){
                    return date('Y-m-d', $model->created_at);
                },
                'filter'=>false,
            ],
            [
                'attribute'=>'updated_at',
                'value'=>function($model){
                    return date('Y-m-d', $model->updated_at);
                },
                'filter'=>false,
            ],
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
					'update' => function ($url, $model) {
						return Html::a(Yii::t('yii', '编辑'), ['view', 'id' => $model->id, 'edit' => 't'], [
							'title' => Yii::t('yii', '编辑'),
							'class' => 'btn btn-success btn-sm'
						]);
					},
					'delete' => function ($url, $model) {
						return Html::a(
							Yii::t('yii', 'Delete'),
							['delete','id' => $model->id, 'id'=> $model->id],
							['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
						);
					},
					'joinblacklist' => function ($url, $model) {
						return empty($model->is_blacklist)?Html::a('封号', [
							'join-blacklist',
							'id' => $model->id
						], [
							'title' => Yii::t('app', '封号'),
							'data-toggle'=>'modal',
							'data-target'=>'#modal',
							'data-id'=>$model->id,
							'class'=>'join-list-btn btn btn-success btn-sm',
						]):Html::a('解除封号', [
							'remove-blacklist',
							'id' => $model->id,
						], [
							'title' => Yii::t('app', '解除封号'),
							'class'=>'join-list-btn btn btn-success btn-sm',
						]);
					},
				],
            ],
			
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        
    ]); Pjax::end(); ?>

</div>
