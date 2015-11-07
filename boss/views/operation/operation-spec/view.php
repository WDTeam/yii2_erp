<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationSpec $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Specs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-spec-view">
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'buttons1' =>'{update}',
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'operation_spec_name',
            'operation_spec_description:ntext',
            'operation_spec_values:ntext',
            'operation_spec_strategy_unit:ntext',
            'created_at:date',
            'updated_at:date',
        ],
        //'deleteOptions'=>[
            //false
        //'url'=>['delete', 'id' => $model->id],
        //'data'=>[
        //'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        //'method'=>'post',
        //],
        //],
        'enableEditMode'=>true,
    ]) ?>

</div>
