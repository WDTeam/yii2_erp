<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\OperationCategory $model
 */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'Operation Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-create">
<!--    <div class="page-header">
        <h1><?php //= Html::encode($this->title) ?></h1>
    </div>-->
    <div class="panel panel-info">
        <div class="panel-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>
