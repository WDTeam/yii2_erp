<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\coupon\CouponRule $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Coupon Rule',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupon Rules'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-rule-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
