<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Worker $model
 */

$this->title = Yii::t('app', '阿姨录入');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
