<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatform */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Platform');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Platform'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="operation-platform-update">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
