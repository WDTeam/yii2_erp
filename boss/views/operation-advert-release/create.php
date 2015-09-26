<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertRelease */

$this->title = 'Create Operation Advert Release';
$this->params['breadcrumbs'][] = ['label' => 'Operation Advert Releases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-release-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
