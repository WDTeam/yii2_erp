<?php


/**
 * @var yii\web\View $this
 * @var common\models\FinanceCompensate $model
 */

$this->title = Yii::t('finance', 'Finance Compensate Create', [
    'modelClass' => 'Finance Compensate Create',
]);

$this->title = $model->isNewRecord?$this->title:"赔偿信息更新";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Compensates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-compensate-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    
</div>
