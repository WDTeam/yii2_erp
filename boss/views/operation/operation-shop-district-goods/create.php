<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationShopDistrictGoods $model
 */

$this->title = 'Create Operation Shop District Goods';
$this->params['breadcrumbs'][] = ['label' => 'Operation Shop District Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-shop-district-goods-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
