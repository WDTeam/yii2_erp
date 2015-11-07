<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('app', 'Operation Specs');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-spec-index">


    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Operation Spec',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'id',
            ],
            'id',
            'operation_spec_name',
            'operation_spec_description:ntext',
            //'operation_spec_values:ntext',
            [
                'format' => 'raw',
                'label' => '规格值',
                'value' => function ($dataProvider) use ($OperationSpecModel) {
                    return $OperationSpecModel::hanldeSpecValues($dataProvider->operation_spec_values);
                },
            ],
            'created_at:date',
            //'updated_at', 

            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {update}',
                'buttons' => [
                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['/operation/operation-spec/update','id' => $model->id]), ['title' => Yii::t('yii', 'Edit'),]);
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']),
//            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
