<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\OperationOrderChannel $model
 */

$this->title = Yii::t('app', '添加', [
    'modelClass' => '订单渠道',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '订单渠道管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-order-channel-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
