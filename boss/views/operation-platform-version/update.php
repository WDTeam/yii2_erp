<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatformVersion */

$this->title = 'Update Operation Platform Version: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Operation Platform Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="operation-platform-version-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
