<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var boss\models\operation\OperationAdvertRelease $model
 */

$this->title = '城市名称: ' . ' ' . $model->city_name;
$this->params['breadcrumbs'][] = ['label' => '更新发布时间', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->city_name, 'url' => ['view', 'city_id' => $model->city_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="operation-advert-release-update">

    <!--h1><?= Html::encode($this->title) ?></h1-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
