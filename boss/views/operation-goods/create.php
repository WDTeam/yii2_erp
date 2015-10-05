<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationGoods $model
 */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'Goods');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Goods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-goods-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
        'priceStrategies' => $priceStrategies,
    ]) ?>

</div>
