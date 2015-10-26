<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '面试管理 - 实操考试');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <div class="row">
        <div class="btn-group col-md-5">
            <?= Html::a(Yii::t('app', '岗前学习'), '/interview/index', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '现场考试'), '/interview/index/exam', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '实操考试'), '/interview/index/oprexam', ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('app', '试工状态'), '/interview/index/test', ['class' => 'btn btn-primary']) ?>
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
            ['attribute' => 'operation_time','value' => function ($model){return empty($model['operation_time']) ? '' : date('Y-m-d', $model['operation_time']);}],
            ['attribute' => 'operation_score','value' => function ($model){$state = [ 1 => '通过', 2 => '未通过', 3 => '未考试'];return empty($model['operation_score']) ? '' : $state[$model['operation_score']];},],
            ['attribute' => 'test_status','value' => function ($model){$state = [ 1 => '已安排试工', 2 => '未安排试工'];return empty($model['test_status']) ? '' : $state[$model['test_status']];},],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{oprexams}',
                'buttons' => ['oprexams' => function ($url, $model, $key) {
                        $url = 'javascript:void(0);';
                        $btns = Html::a('【操作】', $url, ['title' => '操作', 'operation_score' =>$model['operation_score'], 'test_status'=>$model['test_status'], 'uid' => $model->id, 'class' => 'operscore']);
                        return $btns;
                
                },],
                'headerOptions' => ['width' => '80'],
            ],
        ],
    ]); ?>

</div>