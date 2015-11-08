<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\coupon\CouponUserinfo $model
 */

$this->title = Yii::t('app', '优惠券批量绑定', [
    'modelClass' => '优惠券批量绑定',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '优惠券管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-userinfo-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
