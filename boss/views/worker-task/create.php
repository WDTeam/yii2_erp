<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var core\models\worker\WorkerTask $model
 */

$this->title = Yii::t('app', '新建阿姨任务');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Worker Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-task-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
