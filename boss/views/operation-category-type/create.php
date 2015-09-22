<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\OperationCategoryType */

$this->title = 'Create Operation Category Type';
$this->params['breadcrumbs'][] = ['label' => 'Operation Category Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
