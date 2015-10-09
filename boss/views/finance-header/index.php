<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\FinanceHeader;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinanceHeaderSearch $searchModel
 */

$this->title = Yii::t('boss', '添加账单配置');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-header-index hideTemp">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 账单配置查询</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel,'odrinfo'=>$payatainfo,'ordedat' => $ordedatainfo]); ?>
    </div>
    </div>
    <p>
        <?php /* echo Html::a(Yii::t('boss', 'Create {modelClass}', [
    'modelClass' => 'Finance Header',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'finance_header_title',
            'finance_header_name',
            [
                'format' => 'raw',
                'label' => '比对字段',
               'value' => function ($dataProvider) {
                $platform = FinanceHeader::selectname($dataProvider->finance_header_where);
                    return $platform;
                },
                'width' => "100px",
            ],
            'finance_order_channel_name',
   //         'finance_pay_channel_id',
           'finance_pay_channel_name', 
//            'create_time:datetime', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-header/view','id' => $model->id,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
