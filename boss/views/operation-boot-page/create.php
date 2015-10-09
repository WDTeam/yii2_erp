<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OperationBootPage */

$this->title = Yii::t('app','Add').Yii::t('operation','Boot Page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('operation','Operation Boot Page'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-boot-page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'citylist' => $citylist,
        'BootPageCityList' => $BootPageCityList,
    ]) ?>

</div>
