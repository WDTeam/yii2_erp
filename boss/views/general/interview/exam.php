<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '面试管理 - 现场考试');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <div class="row">
        <div class="btn-group col-md-5">
            <?= Html::a(Yii::t('app', '岗前学习'), '/interview/index', ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', '现场考试'), '/interview/index/exam', ['class' => 'btn btn-success']) ?>
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
            ['attribute' => 'online_exam_time','value' => function ($model){return empty($model['online_exam_time']) ? '' : date('Y-m-d', $model['online_exam_time']);},],
            ['attribute' => 'online_exam_score','value' => function ($model){return empty($model['online_exam_score']) ? '' : $model['online_exam_score'];},],
            ['attribute' => 'exam_result','value' => function ($model){$state = [ '1' => '通过', '2' => '不通过', '3' => '未考试'];return empty($model['exam_result']) ? '' : $state[$model['exam_result']];},],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '安排考试情况',
                'template' => '{examxc}',
                'buttons' => ['examxc' => function ($url, $model, $key) {
                if(empty($model->online_exam_mode)){
                    return '';
                }elseif($model->online_exam_mode == '1'){
                    return '【已安排手机考试】';
                }elseif($model->online_exam_mode == '2'){
                    return '【已安排电脑考试】';
                }
                },],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '安排考试',
                'template' => '{examxc}',
                'buttons' => ['examxc' => function ($url, $model, $key) {
//                if(empty($model->online_exam_mode)){
                    return Html::a('【手机考试】', 'javascript:void(0);', ['title' => '手机考试', 'class' => 'online', 'uid' => $model['id'], 'mode' => '1'] ).Html::a('【电脑考试】', Yii::$app->params['fr_domain'].'/study/exam-answer', ['title' => '电脑考试','target'=>'_blank', 'class' => 'online', 'uid' => $model['id'], 'mode' => '2'] );
//                }elseif($model->online_exam_mode == '1'){
//                    return '【已安排手机考试】';
//                }elseif($model->online_exam_mode == '2'){
//                    return Html::a('【已安排电脑考试】', 'javascript:void(0);', ['title' => '已安排电脑考试', 'class' => 'online', 'uid' => $model['id'], 'mode' => '2'] );
//                }
                },],
                'headerOptions' => ['width' => '140'],
            ],
        ],
    ]); ?>

</div>
