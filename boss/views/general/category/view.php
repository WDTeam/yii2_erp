<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Category */

$this->title = Yii::t('app','Update Categories');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app','Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <!--<h1><?php // Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a(Yii::t('app','Update'), ['update', 'id' => $model->cateid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app','Delete'), ['delete', 'id' => $model->cateid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定你要删除这个项目么？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cateid',
            'catename',
            'description:ntext',
        ],
    ]) ?>

</div>
