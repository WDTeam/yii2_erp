<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\FinancePayChannel $model
 */

$this->title = Yii::t('boss', ' {modelClass}', [
    'modelClass' => '支付渠道添加',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Finance Pay Channels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-pay-channel-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
