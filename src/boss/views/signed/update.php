<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Signed */

$this->title = '签约: ' . ' ' . $user->username;
$this->params['breadcrumbs'][] = ['label' => 'Signeds', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->uid, 'url' => ['view', 'id' => $model->uid]];
$this->params['breadcrumbs'][] = '签约';
?>
<div class="signed-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
    ]) ?>

</div>
