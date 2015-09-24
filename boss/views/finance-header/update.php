<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceHeader $model
 */

$this->title = Yii::t('boss', 'Update {modelClass}: ', [
    'modelClass' => 'Finance Header',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Finance Headers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('boss', 'Update');
?>
<div class="finance-header-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
