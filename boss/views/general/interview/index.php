<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
// The widget
use kartik\select2\Select2;
use yii\web\JsExpression;
use boss\models\Interview;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '面试管理 - 岗前学习');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <div class="row">
        <div class="btn-group col-md-5">
            <?= Html::a(Yii::t('app', '岗前学习'), '/interview/index', ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('app', '现场考试'), '/interview/index/exam', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '实操考试'), '/interview/index/oprexam', ['class' => 'btn btn-primary']) ?>
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
            ['attribute' => 'study_time','value' => function ($model){return empty($model['study_time']) ? '' : boss\models\Interview::processSecond($model['study_time']);},],
            ['attribute' => 'study_status','value' => function ($model){$state = ['1' => '未学习', '2' => '学习中','3' => '未通过', '4' => '已通过',];return empty($model['study_status']) ? '' : $state[$model['study_status']];},],
            ['attribute' => 'notice_status','value' => function ($model){$state = [ '1' => '已通知', '2' => '未通知',];return empty($model['notice_status']) ? '' : $state[$model['notice_status']];},],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'template' => '{notice}',
                'buttons' => ['notice' => function ($url, $model, $key) {return Html::a('发送通知', 'javascript:void(0);', ['title' => '发送通知', 'class'=>"sendsms", 'uid'=>$model['id']] );},],
                'headerOptions' => ['width' => '80'],
            ],
        ],
    ]); ?>

</div>