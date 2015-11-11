<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationPlatform */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'Platform');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Platform'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-platform-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
