<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatform */

$this->title = 'Update Operation Platform: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Operation Platforms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="operation-platform-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
