<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Signed */

$this->title = 'Create Signed';
$this->params['breadcrumbs'][] = ['label' => 'Signeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="signed-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
