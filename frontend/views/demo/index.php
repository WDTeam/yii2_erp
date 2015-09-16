<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
// The widget
use kartik\select2\Select2;
use yii\web\JsExpression;
use backend\models\Interview;
use yii\helpers\ArrayHelper;
use frontend\models\DemoSearch;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var frontend\models\DemoSearch $searchModel
 */

$this->title = Yii::t('app', 'Demo');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php $form=ActiveForm::begin() ?>
        <?php
// Without model and implementing a multiple select
echo Select2::widget([
    'name' => 'username',
    'data' => ArrayHelper::map($searchModel::find()->all(),'mobile','username'),
    'options' => [
        'placeholder' => '搜索用户姓名',
        'multiple' => true
    ],
]);

/*******
 * View
 ******/
 
// The controller action that will render the list
$url = \yii\helpers\Url::to(['citylist']);
 

 
// Get the initial city description
$cityDesc = empty($searchModel->DemoSearch) ? '' : DemoSearch::findOne($searchModel->mobile)->username;
 
echo $form->field($searchModel, 'mobile')->widget(Select2::classname(), [
    'initValueText' => $cityDesc, // set the initial display text
    'options' => ['placeholder' => '搜索用户姓名 ...'],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 3,
        'ajax' => [
            'url' => $url,
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
        'templateResult' => new JsExpression('function(results) { return results.mobile; }'),
        'templateSelection' => new JsExpression('function (results) { return results.mobile; }'),
    ],
]);
?>
        <?php ActiveForm::end() ?>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'User',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'common_mobile',
            'id',
            'username',
            'auth_key',
            'password_hash',
//            'password_reset_token', 
//            'email:email', 
//            'idnumber', 
//            'age', 
//            ['attribute'=>'birthday','format'=>['date',(isset(Yii::$app->modules['datecontrol']['displaySettings']['date'])) ? Yii::$app->modules['datecontrol']['displaySettings']['date'] : 'd-m-Y']], 
//            'mobile', 
//            'ecp', 
//            'ecn', 
//            'address', 
//            'city', 
//            'province', 
//            'district', 
//            'whatodo', 
//            'from_type', 
//            'when', 
//            'status', 
//            'created_at', 
//            'updated_at', 
//            'isdel', 
//            'study_status', 
//            'study_time:datetime', 
//            'notice_status', 
//            'online_exam_time:datetime', 
//            'online_exam_score', 
//            'online_exam_mode', 
//            'exam_result', 
//            'operation_time:datetime', 
//            'operation_score', 
//            'test_status', 
//            'test_situation', 
//            'test_result', 
//            'sign_status', 
//            'user_status', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['demo/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 恢复', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
