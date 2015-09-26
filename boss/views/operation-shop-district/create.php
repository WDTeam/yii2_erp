<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationShopDistrict $model
 */

$this->title = Yii::t('operation', 'Create Operation Shop District', [
    'modelClass' => 'Operation Shop District',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Cities'), 'url' => ['operation-city/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('operation', 'Operation Shop Districts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-shop-district-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'OperationShopDistrictCoordinate' => $OperationShopDistrictCoordinate,
    ]) ?>

</div>
