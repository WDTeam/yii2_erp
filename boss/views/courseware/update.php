<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\Courseware $model
 */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Courseware',
]) . ' ' . $model->name;
$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $category->catename, 'url' => ['category/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coursewares'), 'url' => ['index', 'classify_id' => $category->cateid]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update').$model->name;
$this->params['breadcrumbs'][] = ['label'=>Yii::t('app','Update'),'url'=>['update','name'=>$model->name]];
?>
<div class="courseware-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
