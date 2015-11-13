<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationShopDistrictGoods $model
 */

$this->title = 'Update Operation Shop District Goods: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Operation Shop District Goods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="operation-shop-district-goods-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
