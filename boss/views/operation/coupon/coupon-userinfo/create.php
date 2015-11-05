<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\operation\coupon\CouponUserinfo $model
 */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Coupon Userinfo',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupon Userinfos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-userinfo-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
