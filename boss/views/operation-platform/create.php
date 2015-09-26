<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatform */

$this->title = 'Create Operation Platform';
$this->params['breadcrumbs'][] = ['label' => 'Operation Platforms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-platform-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
