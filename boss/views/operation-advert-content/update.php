<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertContent */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Advert Content');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Content'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-content-update">

    <?= $this->render('_form', [
        'model' => $model,
        'cityList' => $cityList,
        'platformList' => $platformList,
        'versionList' => $versionList,
    ]) ?>

</div>
