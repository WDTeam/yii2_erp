<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Worker $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Worker',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="worker-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
