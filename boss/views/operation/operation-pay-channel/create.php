<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\OperationPayChannel $model
 */

$this->title = Yii::t('app', '添加', [
    'modelClass' => '支付渠道',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '支付渠道管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-pay-channel-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
