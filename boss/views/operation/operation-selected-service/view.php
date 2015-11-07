<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationGoods $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Selected Service'), 'url' => ['operation/operation-selected-service']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-selected-service-view">
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'selected_service_scene',
            'selected_service_area',
            'selected_service_sub_area',
            'selected_service_standard',
            ['attribute' => 'selected_service_area_standard', 'value' => ($model->selected_service_area_standard == 1) ? '面积小于100平米' : '面积小于100平米'],
            'created_at:datetime',
            'updated_at:datetime',
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->id],
            'data'=>[
                'method'=>'post',
            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
