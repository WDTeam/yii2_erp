<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model boss\models\ComplaintOrder */

$this->title = 'Update Complaint Order: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Complaint Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="complaint-order-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
