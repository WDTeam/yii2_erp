<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model boss\models\ComplaintOrder */

$this->title = 'Create Complaint Order';
$this->params['breadcrumbs'][] = ['label' => 'Complaint Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="complaint-order-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
