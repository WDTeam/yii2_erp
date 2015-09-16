<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Questions');
$this->params['breadcrumbs'][] = Html::a('服务分类','/category/index');
$this->params['breadcrumbs'][] = Html::a($courseware->name,'/courseware/index?classify_id='.$courseware->classify_id);
//$this->params['breadcrumbs'][] = ['label' => $category->catename, 'url' => ['view', 'id' => $category->cateid]];
//$this->params['breadcrumbs'][] = $category->catename;
//$this->params['breadcrumbs'][] = Html::a($courseware->name, '');
  //$this->params['breadcrumbs'][] = ['label' => $courseware->name, 'url' => ['index', 'courseware_id' => $courseware->classify_id]];
//$this->params['breadcrumbs'][] = $courseware->name;
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = Html::a($this->title,'');
?>
<div class="question-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a(Yii::t('app', 'Create'), [ 
            'create', 
            'courseware_id'=>$courseware->id,
            'category_id'=>$courseware->classify_id,
        ], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//             ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
//             'courseware_id',
            'options:ntext',
            [
                'header'=>'正确答案',
                'attribute'=>'correct_options'
            ],
//             'is_multi',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
