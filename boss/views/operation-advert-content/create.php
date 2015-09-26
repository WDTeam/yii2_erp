<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertContent */

$this->title = 'Create Operation Advert Content';
$this->params['breadcrumbs'][] = ['label' => 'Operation Advert Contents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-content-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
