<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model boss\models\Auth */

$this->title = Yii::t('app', 'Update Auth') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auths'), 'url' => ['index']];
// $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
