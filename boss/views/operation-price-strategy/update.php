<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OperationPriceStrategy */

$this->title = Yii::t('app', 'Update').'价格策略';
$this->params['breadcrumbs'][] = ['label' => '价格策略', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-price-strategy-update">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
