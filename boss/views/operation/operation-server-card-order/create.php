<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardOrder $model
 */

$this->title = Yii::t('app', '新增', [
    'modelClass' => 'Operation Server Card Order',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡订单管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-server-card-order-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
