<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinanceOrderChannel $model
 */

$this->title = Yii::t('boss', '{modelClass}', [
    'modelClass' => '订单渠道添加',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', '渠道管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-order-channel-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
