<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var core\models\worker\WorkerTask $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Worker Task',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="worker-task-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
