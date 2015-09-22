<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Worker $model
 */

$this->title = Yii::t('app', '录入新阿姨', [
    'modelClass' => 'Worker',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$worker_ext->worker_is_health=1;
$worker_ext->worker_is_insurance=1;
$worker_ext->worker_sex=0;
$worker->worker_type=1;

?>
<div class="worker-create">
    <div class="page-header">
    </div>
    <?= $this->render('_form', [
        'worker' => $worker,
        'worker_ext'=>$worker_ext
    ]) ?>

</div>
