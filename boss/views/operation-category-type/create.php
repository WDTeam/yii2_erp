<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OperationCategoryType */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'Category Types');//'Create Operation Category Type';
$this->params['breadcrumbs'][] = Html::a(Yii::t('app', 'Category Types'), ['index']);//['label' => 'Operation Category Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-type-create">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'priceStrategies' => $priceStrategies,
    ]) ?>

</div>
