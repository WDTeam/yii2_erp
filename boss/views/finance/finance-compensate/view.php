<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\FinanceCompensate $model
 */

$this->title = '赔偿详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Compensates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-compensate-view">
  <div class="panel panel-info">
        <div class="panel-body">

                <?= DetailView::widget([
                        'model' => $model,
                        'condensed'=>false,
                        'hover'=>true,
                        'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
                        'panel'=>[
                        'heading'=>$this->title,
                        'type'=>DetailView::TYPE_INFO,
                    ],
                    'attributes' => [
                        'id',
                        'finance_compensate_oa_code',
                        'finance_complaint_id',
                        'worker_id',
                        'customer_id',
                        'finance_compensate_coupon',
                        'finance_compensate_money',
                        'finance_compensate_reason:ntext',
                        'finance_compensate_proposer',
                        'finance_compensate_auditor',
                        'comment:ntext',
                        'updated_at',
                        'created_at',
                        'isdel',
                    ],
                    'deleteOptions'=>[
                    'url'=>['delete', 'id' => $model->id],
                    'data'=>[
                    'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method'=>'post',
                    ],
                    ],
                    'enableEditMode'=>true,
                ]) ?>
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
</div>
