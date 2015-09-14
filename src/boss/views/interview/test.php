<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '面试管理 - 试工状态');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <div class="row">
        <div class="btn-group col-md-5">
            <?= Html::a(Yii::t('app', '岗前学习'), '/interview/index', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '现场考试'), '/interview/index/exam', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '实操考试'), '/interview/index/oprexam', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '试工状态'), '/interview/index/test', ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('app', '签约状态'), '/interview/index/signed', ['class' => 'btn btn-primary']) ?>
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
            'username',
            'idnumber',
            'mobile',
            ['attribute' => 'created_at','value' => function ($model){return empty($model['created_at']) ? '' : date('Y-m-d', $model['created_at']);},],
            ['attribute' => 'test_time','value' => function ($model){return empty($model['test_time']) ? '' : date('Y-m-d', $model['test_time']);},'label' => '安排试工时间'],
            ['attribute' => 'test_situation','value' => function ($model){
                $state = ['1' => '零投诉', '2' => '有投诉'];
                if($model['test_situation'] === 0){
                    return $state[1];
                }elseif($model['test_situation'] == ''){
                    return '';
                }else{
                    $model['test_situation'] > 0 ? $st = 2 : $st = 1;
                }
                return $state[$st];
                },],
            ['attribute' => 'test_result','value' => function ($model){$state = [ '1' => '通过', '2' => '不通过'];return empty($model['test_result']) ? '' : $state[$model['test_result']];},],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{tested}',
                'buttons' => ['tested' => function ($url, $model, $key){return $model->test_result == 1 ? '' : Html::a('编辑', 'javascript:void(0);', ['title' => '编辑', 'uid' => $model->id, 'class' => 'testwk'] );},],
                'headerOptions' => ['width' => '80'],
            ],
        ],
    ]); ?>

</div>
