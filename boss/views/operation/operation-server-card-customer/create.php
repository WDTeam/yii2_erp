<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\operation\OperationServerCardCustomer $model
 */

$this->title = Yii::t('app', '新增客户服务卡', [
    'modelClass' => 'Server Card Customer',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '客户服务卡管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-server-card-customer-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
