<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use yii\widgets\Pjax;
use common\models\Shop;
use yii\helpers\ArrayHelper;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨结算');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div id = "manualSettle" class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 阿姨搜索</h3>
        </div>

        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                 'type' => ActiveForm::TYPE_HORIZONTAL,
                 //'id' => 'login-form-inline',
                 'action' => ['index'],
                 'method' => 'get',
                 ]);


            ?>
            <div class='col-md-6'>
                <?= $form->field($model, 'workerPhone') ?>
            </div>
            <div class='col-md-2' >
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
            <div class='col-md-4'>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

        <div class="panel-heading">
                <h3 class="panel-title">阿姨信息</h3>
        </div>
        <div class="panel-body">
            <div class='col-md-2'>
                阿姨姓名
            </div>
            <div class='col-md-2'>
                手机号
            </div>
            <div class='col-md-2'>
                入职日期
            </div>
            <div class='col-md-2'>
                阿姨类型
            </div>
            <div class='col-md-2'>
                结算周期
            </div>
            <div class='col-md-2'>
                上次结算时间
            </div>
        </div>
        <div class="panel-body ">
            <div class='col-md-2 box-solid'>
                <?=  $model->workerName; ?>
            </div>
            <div class='col-md-2'>
                13456789000
            </div>
            <div class='col-md-2'>
                2015-09-10 17:30:00
            </div>
            <div class='col-md-2'>
                全职全日
            </div>
            <div class='col-md-2'>
                月结
            </div>
            <div class='col-md-2'>
                2015-09-10 17:30:00
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">结算明细</label>
        <?=

            Html::a('结算', ['worker-manual-settlement-done?FinanceSettleApplySearch[workerId]='.$model->workerId], ['class' => 'btn btn-success ']);

         ?>
        </div>
        <div class="panel-body">
            <div class='col-md-1'>
                完成总单量
            </div>
            <div class='col-md-1'>
                现金订单
            </div>
            <div class='col-md-1'>
                收取现金
            </div>
            <div class='col-md-1'>
                非现金订单
            </div>
            <div class='col-md-1'>
                工时费应结
            </div>
            <div class='col-md-1'>
                完成任务
            </div>
            <div class='col-md-1'>
                任务奖励
            </div>
            <div class='col-md-1'>
                小保养订单
            </div>
            <div class='col-md-1'>
                小保养
            </div>
            <div class='col-md-1'>
                应结
            </div>
        </div>
        <div class="panel-body ">
            <div class='col-md-1'>
                250
            </div>
            <div class='col-md-1'>
                30
            </div>
            <div class='col-md-1'>
                2000.00
            </div>
            <div class='col-md-1'>
                100
            </div>
            <div class='col-md-1'>
                4000.00
            </div>
            <div class='col-md-1'>
                20
            </div>
            <div class='col-md-1'>
                2000.00
            </div>
            <div class='col-md-1'>
                20
            </div>
            <div class='col-md-1'>
                2000.00
            </div>
            <div class='col-md-1'>
                8000.00
            </div>
        </div>

        
    </div>
</div>

