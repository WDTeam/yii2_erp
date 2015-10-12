<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationShopDistrictGoods $model
 */

$this->title = Yii::t('operation', 'Update {modelClass}: ', [
    'modelClass' => 'Operation Shop District Goods',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('operation', 'Operation Shop District Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('operation', 'Update');
?>
<div class="operation-shop-district-goods-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
