<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Question */

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Questions'), ''];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Questions'), ''];
$this->params['breadcrumbs'][] = Html::a(Yii::t('app','Questions'),'/question/index?courseware_id='.$model->courseware_id);

//$this->params['breadcrumbs'][] = ['label' => $courseware->name, 'url' => ['view', 'courseware_id' => $courseware->id]];
//$this->params['breadcrumbs'][] = Html::a($courseware->name,'/question/index?classify_id='.$courseware->classify_id);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a(Yii::t('app','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定你想删除这个项目?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'courseware_id',
            'options:ntext',
            'is_multi',
            'correct_options',
        ],
    ]) ?>

</div>
