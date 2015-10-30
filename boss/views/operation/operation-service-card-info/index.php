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
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php  echo $this->render('_search', ['model' => $searchModel,'config'=>$config,]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Service Card Info',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['operation/operation-service-card-info/view','id' => $model->id,'edit'=>'t']), [
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
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 增加', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
