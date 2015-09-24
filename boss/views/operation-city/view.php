<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\OperationCity $model
 */

$this->title = Yii::t('app', 'Look').$model->city_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'View Detail');
?>
<div class="operation-city-view">
<!--    <div class="page-header">
        <h1><?php //= Html::encode($this->title) ?></h1>
    </div>-->


    <?= DetailView::widget([
        'model' => $model,
        'condensed'=>false,
        'hover'=>true,
        'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
//        'panel'=>[
//            'heading'=> $model->operation_city_name, 
//            'type'=>DetailView::TYPE_INFO,
//        ],
        'attributes' => [
            'id',
            'province_name',
            'city_name',
            ['attribute' => 'operation_city_is_online', 'value' => $model->operation_city_is_online == 1 ? '已上线': '已下线'],
            ['attribute' => 'created_at', 'value' => empty($model->created_at)? '' : date('Y-m-d H:i:s', $model->created_at)],
            ['attribute' => 'updated_at', 'value' => empty($model->updated_at)? '' : date('Y-m-d H:i:s', $model->updated_at)],
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->id],
//            'data'=>[
//                'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
//                'method'=>'post',
//            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
