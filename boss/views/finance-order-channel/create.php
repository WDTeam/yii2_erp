<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceOrderChannel $model
 */

$this->title = Yii::t('boss', '{modelClass}', [
    'modelClass' => '订单渠道添加',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Finance Order Channels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-order-channel-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
