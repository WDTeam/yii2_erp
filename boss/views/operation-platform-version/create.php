<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatformVersion */

$this->title = 'Create Operation Platform Version';
$this->params['breadcrumbs'][] = ['label' => 'Operation Platform Versions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-platform-version-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
