<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\ServerCard $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Server Card',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Server Cards'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="server-card-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
