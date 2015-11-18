<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\Operation\OperationSpec $model
 */

$this->title = Yii::t('app', 'Create').Yii::t('app', 'Spec');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Specs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-spec-create">
    <?= $this->render('_form', [
        'model'  => $model,
        'action' => 'create',
    ]) ?>

</div>
