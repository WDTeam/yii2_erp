<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\OperationCategory $model
 */

$this->title = Yii::t('app', 'Look').Yii::t('app', 'Operation Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-view">
<!--    <div class="page-header">
        <h1><?php //= Html::encode($this->title) ?></h1>
    </div>-->


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
//            'panel'=>['heading'=>$this->title, 'type'=>DetailView::TYPE_INFO],
        'attributes' => [
            'id',
            'operation_category_name',
            ['attribute' => 'created_at', 'value' => date('Y-m-d H:i:s', $model->created_at)],
            ['attribute' => 'updated_at', 'value' => date('Y-m-d H:i:s', $model->updated_at)],
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->id],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
