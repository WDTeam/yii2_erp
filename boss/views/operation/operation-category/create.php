<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var dbbase\models\OperationCategory $model
 */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'Operation Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-category-create">

    <?= $this->render('_form', [
        'model' => $model,
        'action' => 'create'
    ]) ?>

</div>
