<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\FinancePopOrder $model
 */

$this->title = Yii::t('app', '{modelClass}', [
    'modelClass' => '添加对账数据',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '对账列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-pop-order-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
