<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Courseware $model
 */

$this->title = '创建课件';
$this->params['breadcrumbs'][] = ['label' => $category->catename, 'url' => ['category/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coursewares'), 'url' => ['index', 'classify_id' => $category->cateid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courseware-create">
<!--    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>-->
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
