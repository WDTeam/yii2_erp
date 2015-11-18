<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\OperationCategory $model
 */

$this->title = Yii::t('app', 'Update').Yii::t('app', 'Operation Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-update">

    <?= $this->render('_form', [
        'model' => $model,
        'action' => 'update'
    ]) ?>

</div>
