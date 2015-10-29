<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\OperationCategory $model
 */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Operation Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Categories'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-update">

    <!--<h1><?php //= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
