<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationSpec $model
 */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Spec');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Specs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="operation-spec-update">

    <?= $this->render('_form', [
        'model'  => $model,
        'action' => 'update',
    ]) ?>

</div>
