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
$this->title = Yii::t('app', '门店结算详情');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div id = "manualSettle" class="panel panel-info">
        <div class="panel-heading">
                <h3 class="panel-title">门店信息</h3>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-2'>
                公司姓名
            </div>
            <div class='col-md-2'>
                门店
            </div>
            <div class='col-md-2'>
                阿姨数量
            </div>
            <div class='col-md-2'>
                联系人
            </div>
            <div class='col-md-2'>
                联系电话
            </div>
            <div class='col-md-2'>
                上次结算时间
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-2'>
                爱佳家政
            </div>
            <div class='col-md-2'>
                爱佳家政北京双井店
            </div>
            <div class='col-md-2'>
                20
            </div>
            <div class='col-md-2'>
                张三
            </div>
            <div class='col-md-2'>
                13456789000
            </div>
            <div class='col-md-2'>
                2015-09-10 17:30:00
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">门店结算明细</label>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-6'>
                完成总单量
            </div>
            <div class='col-md-6'>
                管理费
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-6'>
                250
            </div>
            <div class='col-md-6'>
                2500
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">阿姨结算明细</label>
        </div>
        <div>
            
             <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $orderIncomeDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute'=>'worker_idcard',
                 'content'=>function($model,$key,$index)
                        {return  Html::a('<u>'.$model->order_id.'</u>',[Yii::$app->urlManager->createUrl(['order/view/','id' => $model->order_id])],['target'=>'_blank']);}],
            'worker_name',
            'worker_phone',
            'worker_type', 
            'order_count', 
            'manage_fee', 
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
    ]); Pjax::end(); ?>
        </div>
    </div>
</div>

