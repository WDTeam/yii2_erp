<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceCompensate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-compensate-form">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">赔偿信息录入</h3>
        </div>
        <div class="panel-body">
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

        'finance_compensate_oa_code'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请录入 OA批号...', 'maxlength'=>40]], 
        'finance_compensate_money'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请录入  赔偿金额...', 'maxlength'=>10]], 
        'finance_compensate_coupon'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请录入  优惠券,可能是多个优惠券，用分号分隔...', 'maxlength'=>150]], 
        'finance_compensate_reason'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'请录入 赔偿原因...','rows'=> 6]], 
            ]


        ]);
        ?>
        </div>
    </div>
    <div class="panel panel-info">
    <div class="panel-heading">
            <label class="panel-title">投诉信息</label>
        </div>
        <div class="panel-body">
            <div class='col-md-2'>
                投诉Id
            </div>
            <div class='col-md-2'>
                订单Id
            </div>
            <div class='col-md-2'>
                投诉对象
            </div>
            <div class='col-md-2'>
                投诉对象电话
            </div>
            <div class='col-md-2'>
                投诉类型
            </div>
            <div class='col-md-2'>
                投诉详情
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-2'>
                <?php echo Html::a('<u>1234</u>',[Yii::$app->urlManager->createUrl(['order/view/','id' => 1])],['data-pjax'=>'0','target' => '_blank',]) ?>
            </div>
            <div class='col-md-2'>
                <?php echo Html::a('<u>5678</u>',[Yii::$app->urlManager->createUrl(['order/view/','id' => 1])],['data-pjax'=>'0','target' => '_blank',]) ?>
            </div>
            <div class='col-md-2'>
                陈阿姨
            </div>
            <div class='col-md-2'>
                13810068888
            </div>
            <div class='col-md-2'>
               物品损坏
            </div>
            <div class='col-md-2'>
                拖地的时候把木质地板拖坏了
            </div>
        </div>
    

</div>
    <?php
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>
