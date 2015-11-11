<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatform */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Platform');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Platform'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update').Yii::t('app', 'Platform');
?>
<div class="operation-platform-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
