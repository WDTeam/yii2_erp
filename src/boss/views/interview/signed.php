<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '面试管理 - 签约状态');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <div class="row">
        <div class="btn-group col-md-5">
            <?= Html::a(Yii::t('app', '岗前学习'), '/interview/index', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '现场考试'), '/interview/index/exam', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '实操考试'), '/interview/index/oprexam', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '试工状态'), '/interview/index/test', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '签约状态'), '/interview/index/signed', ['class' => 'btn btn-success']) ?>
        </div>
        <div class="input-group input-group-sm col-md-5">
            <?php $form = ActiveForm::begin(); ?>
            <?= Html::textInput('fd',$fd,['class'=>'form-control','placeholder' => '输入用户名、手机号或身份证号']) ?>
            <span class="input-group-btn">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-info btn-flat']) ?>
            </span>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<!--    <p>
        <?= Html::a(Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn',],
            'username',
            'idnumber',
            'mobile',
            ['attribute' => 'created_at','value' => function ($model){return empty($model['created_at']) ? '' : date('Y-m-d', $model['created_at']);},],
            ['attribute' => 'study_status','value' => function ($model){return $model['study_status']==4 ? '已通过' : '';},'label' =>'岗前学习结果'],
            ['attribute' => 'online_exam_score','value' => function ($model){return empty($model['online_exam_score']) ? '' : $model['online_exam_score'];},], 
            ['attribute' => 'operation_score','value' => function ($model){$state = [ '1' => '通过', '2' => '不通过', '3' => '未考试'];return empty($model['operation_score']) ? '' : $state[$model['operation_score']];},],
            ['attribute' => 'test_result','value' => function ($model){$state = [ '1' => '通过', '2' => '未通过',];return empty($model['test_result']) ? '' : $state[$model['test_result']];},], 
            ['attribute' => 'sign_status','value' => function ($model) {$state = [ '1' => '已签约', '2' => '未签约',];return empty($model['sign_status']) ? '' : $state[$model['sign_status']];},], 
//            ['class' => 'yii\grid\ActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{signing}',
                'buttons' => ['signing' => function ($url, $model, $key) {
                if($model['study_status'] != '4' || $model['exam_result'] != '1' || $model['operation_score'] != '1'|| $model['test_result'] != '1'){
                    return '立即签约';
                }else{
                    return $model->sign_status == 1 ? Html::a('立即解约', 'javascript:void(0);', ['title' => '立即解约', 'class'=>'fire', 'uid' => $model->id] ) : Html::a('立即签约', '/signed/update/'.$model->id, ['title' => '立即签约'] );
                }
                },],
                'headerOptions' => ['width' => '80'],
            ],
        ],
    ]); ?>

</div>
