<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model dbbase\models\OperationCategoryType */

//$this->title = 'Update Operation Category Type: ' . ' ' . $model->id;
//$this->params['breadcrumbs'][] = ['label' => 'Operation Category Types', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Category Types');//'Create Operation Category Type';
$this->params['breadcrumbs'][] = Html::a($category->operation_category_name.' - '.Yii::t('app', 'Category Types'), ['index', 'category_id'=>$category->id]);//['label' => 'Operation Category Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-type-update">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category,
        'priceStrategies' => $priceStrategies,
    ]) ?>

</div>
