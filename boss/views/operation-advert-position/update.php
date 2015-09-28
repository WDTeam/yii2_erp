<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertPosition */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Advert Position');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Position'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="operation-advert-position-update">

    <?= $this->render('_form', [
        'model' => $model,
        'platforms' => $platforms,
        'versions' => $versions,
    ]) ?>

</div>
