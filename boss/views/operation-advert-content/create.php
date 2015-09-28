<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertContent */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'Advert Content');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Content'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-content-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
