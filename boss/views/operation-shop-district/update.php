<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationShopDistrict $model
 */

$this->title = Yii::t('operation', 'Update Operation Shop District', [
    'modelClass' => 'Operation Shop District',
]) . ' ';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Cities'), 'url' => ['operation-city/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('operation', 'Operation Shop Districts'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('operation', 'Update Operation Shop District');
?>
<div class="operation-shop-district-update">
    
    <?= $this->render('_form', [
        'model' => $model,
        'citymodel' => $citymodel,
        'areaList' => $areaList,
        'OperationShopDistrictCoordinate' => $OperationShopDistrictCoordinate,
    ]) ?>

</div>
