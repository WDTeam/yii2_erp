<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\ServerCard $model
 */

$this->title = Yii::t('app', '新增服务卡', [
    'modelClass' => 'Server Card',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '服务卡信息管理'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="server-card-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
