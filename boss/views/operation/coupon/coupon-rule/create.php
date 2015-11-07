<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\coupon\CouponRule $model
 */

$this->title = Yii::t('app', '添加规则', [
    'modelClass' => '添加规则',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '添加规则管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-rule-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
