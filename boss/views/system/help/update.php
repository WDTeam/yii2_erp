<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\Help $model
 */

$this->title = Yii::t('boss', 'Update {modelClass}: ', [
    'modelClass' => 'Help',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Helps'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('boss', 'Update');
?>
<div class="help-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
