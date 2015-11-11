<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var core\models\finance\FinanceCompensate $searchModel
 */

$this->title = Yii::t('finance', 'Finance Compensates Confirm List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-compensate-index">
    <div id = "manualSettle" class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 阿姨搜索</h3>
        </div>

        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                 'type' => ActiveForm::TYPE_HORIZONTAL,
                 'method' => 'get',
                 ]);
            ?>
            <div class='col-md-6'>
                <?= $form->field($searchModel, 'worker_tel')->textInput(['name'=>'worker_tel','value'=>$searchModel->worker_tel]) ?>
            </div>
            <div class='col-md-2' style="margin-top: 22px;">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'finance_compensate_code',
            'finance_compensate_oa_code',
            'finance_complaint_code',
            'order_code',
            'finance_compensate_reason:ntext', 
            'finance_compensate_money', 
            'finance_compensate_coupon_money', 
            'finance_compensate_total_money',
            'finance_compensate_worker_money',
            'finance_compensate_company_money',
            'finance_compensate_insurance_money',
            'worker_name',
            'worker_tel',
            'finance_compensate_proposer', 
           [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{view} {agree} {disagree}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl(['/finance/finance-compensate/view', 'id' => $model->id],[]), [
                            'title' => Yii::t('yii', '查看'),'data-pjax'=>'0','target' => '_blank',
                        ]);
                    },
                    'agree' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', Yii::$app->urlManager->createUrl(['/finance/finance-compensate/review', 'id' => $model->id, 'is_ok'=>1,]), [
                            'title' => Yii::t('yii', '确认打款'),
                            'class'=>'agree',
                        ]);
                    },
                    'disagree' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-remove" ></span>',
                            [
                                '/finance/finance-compensate/review-failed-reason',
                                'id' => $model->id, 
                                'is_ok'=>0,
                            ]
                            ,
                            [
                                'title' => Yii::t('yii', '不通过'),
                                'data-toggle' => 'modal',
                                'data-target' => '#reasonModal',
                                'class'=>'disagree',
                                'data-id'=>$model->id,
                            ]);
                        },
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
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>
        </div>
</div>

  <?php 
         
            $js=<<<JS
                    $(".agree").click(
                        function(){
                            if(confirm("确认该笔赔偿已付款是吗?")){
                                return true;
                            }else{
                                return false;
                            }
                        }
                    );
                    $('.disagree').click(function() {
                        $('#reasonModal .modal-body').html('加载中……');
                        $('#reasonModal .modal-body').eq(0).load(this.href);
                    });
JS;
        $this->registerJs(
                $js
        );
         ?>

<?php echo Modal::widget([
            'header' => '<h4 class="modal-title">请输入不通过原因</h4>',
            'id'=>'reasonModal',
            'options'=>[
                'size'=>'modal-sm',
            ],
        ]);
?>
