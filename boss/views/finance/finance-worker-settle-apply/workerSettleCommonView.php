<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$review_section = -1;
if(isset($model->review_section)){
    $review_section = $model->review_section;
}
?>
        <div class="panel-heading">
                <h3 class="panel-title">阿姨信息</h3>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-1'>
                阿姨姓名
            </div>
            <div class='col-md-1'>
                手机号
            </div>
            <div class='col-md-2'>
                入职日期
            </div>
            <div class='col-md-1'>
                阿姨类型
            </div>
            <div class='col-md-1'>
                结算类型
            </div>
            <div class='col-md-2'>
                结算周期
            </div>
            <div class='col-md-2'>
                本次结算时间
            </div>
            <div class='col-md-2'>
                上次结算时间
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='col-md-1'>
                <?=  $model->worker_name; ?>
            </div>
            <div class='col-md-1'>
                <?=  $model->worker_tel; ?>
            </div>
            <div class='col-md-2'>
                <?=  date('Y:m:d H:i:s',$model->workerOnboardTime); ?>
            </div>
            <div class='col-md-1'>
                <?=  $model->workerTypeDes; ?>
            </div>
            <div class='col-md-1'>
                <?=  $model->finance_worker_settle_apply_cycle_des; ?>
            </div>
            <div class='col-md-2'>
                <?=  date('Y-m-d',$model->finance_worker_settle_apply_starttime).'至'.date('Y-m-d',$model->finance_worker_settle_apply_endtime); ?>
            </div>
            <div class='col-md-2'>
                <?=  date('Y:m:d H:i:s',time()); ?>
            </div>
            <div class='col-md-2'>
                <?php
                    if($model->latestSettleTime != null){
                        echo date('Y:m:d H:i:s',$model->latestSettleTime); 
                    }else{
                        echo "之前未成功结算";
                    }
                ?>
            </div>
        </div>
        <div class="panel-heading">
            <label class="panel-title">结算明细</label>
        <?php
            if($review_section > 0 ){
                echo Html::a('结算', ['worker-manual-settlement-done?worker_id='.$model->worker_id.'&settle_type='.$model->settle_type.'&review_section='.$model->review_section], ['class' => 'btn btn-success ']);
            }
         ?>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='settleDetail'>
                完成订单总数
            </div>
            <div class='settleDetail'>
                订单费用小计
            </div>
            <div class='settleDetail'>
                底薪补贴
            </div>
            <div class='settleDetail'>
                完成任务
            </div>
            <div class='settleDetail'>
                任务小计
            </div>
            <div class='settleDetail'>
                扣款小计
            </div>
            <div class='settleDetail'>
                本次应结合计
            </div>
            <div class='settleDetail'>
                现金订单
            </div>
            <div class='settleDetail'>
                已收现金小计
            </div>
            <div class='settleDetail'>
                本次应付合计
            </div>
        </div>
        <div class="panel-body settle-detail-body">
            <div class='settleDetail'>
                
                <?php
                    if($model->finance_worker_settle_apply_order_count > 0){
                        echo '<span class = "ordercount" style = "cursor:pointer"><u>'.$model->finance_worker_settle_apply_order_count.'</u></span>';
                    }else{
                        echo $model->finance_worker_settle_apply_order_count;
                    }
                ?>
            </div>
            <div class='settleDetail'>
                 <?php
                    echo $model->finance_worker_settle_apply_order_money;
                ?>
            </div>
            <div class='settleDetail'>
               <?php
                    echo $model->finance_worker_settle_apply_base_salary_subsidy;
                ?>
            </div>
            <div class='settleDetail'>
                <?php
                    if($model->finance_worker_settle_apply_task_count > 0){
                            echo '<span class = "taskcount" style = "cursor:pointer"><u>'.$model->finance_worker_settle_apply_task_count.'</u></span>';
                        }else{
                            echo $model->finance_worker_settle_apply_task_count;
                        }
                ?>
            </div>
            <div class='settleDetail'>
                 <?php
                    echo $model->finance_worker_settle_apply_task_money;
                ?>
            </div>
            <div class='settleDetail'>
                <?php
                    if($model->finance_worker_settle_apply_money_deduction > 0){
                            echo '<span class = "deductionmoney" style = "cursor:pointer"><u>'.$model->finance_worker_settle_apply_money_deduction.'</u></span>';
                        }else{
                            echo $model->finance_worker_settle_apply_money_deduction;
                        }
                ?>
            </div>
            <div class='settleDetail'>
                <?php
                    echo $model->finance_worker_settle_apply_money_except_cash;
                ?>
            </div>
            <div class='settleDetail'>
                <?php
                    if($model->finance_worker_settle_apply_order_cash_count > 0){
                            echo '<span class = "cashordercount" style = "cursor:pointer"><u>'.$model->finance_worker_settle_apply_order_cash_count.'</u></span>';
                        }else{
                            echo $model->finance_worker_settle_apply_order_cash_count;
                        }
                ?>
            </div>
            <div class='settleDetail'>
                <?php
                    echo $model->finance_worker_settle_apply_order_cash_money;
                ?>
            </div>
            <div class='settleDetail'>
                <?php
                    echo $model->finance_worker_settle_apply_money;
                ?>
            </div>
        </div>
        
<?php 
           echo '<div id = "allOrderInfo" >';
                Pjax::begin(); echo GridView::widget([
               'dataProvider' => $orderDataProvider,
               'columns' => [
                   ['class' => 'yii\grid\SerialColumn'],
                   ['attribute'=>'order_code',
                       'header' => Yii::t('app', '订单号'),
                        'content'=>function($model,$key,$index)
                               {return  Html::a('<u>'.$model['order_code'].'</u>',[Yii::$app->urlManager->createUrl(['order/order/edit/','id' => $model['order_code']])],['data-pjax'=>'0','target' => '_blank',]);}
                    ],
                    ['attribute'=>'order_service_type_name',
                       'header' => Yii::t('app', '服务类型'),],
                    ['attribute'=>'order_channel_name',
                       'header' => Yii::t('app', '渠道'),],
                    [
                       'header' => Yii::t('app', '支付方式'),
                        'attribute' => 'order_pay_type_des',
                    ],
                    ['attribute'=>'order_booked_begin_time',
                       'header' => Yii::t('app', '服务开始时间'),
                        'content'=>function($model,$key,$index){
                                   return date('Y-m-d h:i:s',$model['order_booked_begin_time']);
                       },
                    ], 
                    ['attribute'=>'order_booked_count',
                       'header' => Yii::t('app', '服务工时（小时）'),
                        'content'=>function($model,$key,$index){
                                   return $model['order_booked_count'];
                       },
                    ], 
                    ['attribute'=>'order_unit_money',
                       'header' => Yii::t('app', '费率（元/小时）'),
                    ], 
                    ['attribute'=>'order_money',
                       'header' => Yii::t('app', '订单总金额（元）'),
                    ], 
                    [
                       'header' => Yii::t('app', '优惠金额（元）'),
                        'content'=>function($model,$key,$index){
                                   return 0;
                       },
                    ],
                    [
                       'attribute'=>'finance_worker_order_income_discount_amount',
                       'header' => Yii::t('app', '用户支付金额（元）'),
                    ],
                    [
                       'attribute'=>'order_pay_money',
                       'header' => Yii::t('app', '阿姨结算金额（元）'),
                    ],
               ],
               'responsive'=>true,
               'hover'=>true,
               'condensed'=>true,
               'floatHeader'=>true,
           'panel' => [
             'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> 所有订单明细 </h3>',
            ],
           ]); Pjax::end(); 
           echo '</div>';
            ?>
        
        
            
<?php 
            echo '<div id = "cashOrderInfo" style = "display:none">';
            Pjax::begin(); echo GridView::widget([
               'dataProvider' => $cashOrderDataProvider,
               'columns' => [
                   ['class' => 'yii\grid\SerialColumn'],
                   ['attribute'=>'order_code',
                       'header' => Yii::t('app', '订单号'),
                        'content'=>function($model,$key,$index)
                               {return  Html::a('<u>'.$model['order_code'].'</u>',[Yii::$app->urlManager->createUrl(['order/order/edit/','id' => $model['order_code']])],['data-pjax'=>'0','target' => '_blank',]);}
                    ],
                    ['attribute'=>'order_service_type_name',
                       'header' => Yii::t('app', '服务类型'),],
                    ['attribute'=>'order_channel_name',
                       'header' => Yii::t('app', '渠道'),],
                    [
                       'header' => Yii::t('app', '支付方式'),
                        'attribute' => 'order_pay_type_id',
                    ],
                    ['attribute'=>'order_booked_begin_time',
                       'header' => Yii::t('app', '服务开始时间'),
                        'content'=>function($model,$key,$index){
                                   return date('Y-m-d h:m:s',$model['order_booked_begin_time']);
                       },
                    ], 
                    ['attribute'=>'order_booked_count',
                       'header' => Yii::t('app', '服务工时（小时）'),
                        'content'=>function($model,$key,$index){
                                   return $model['order_booked_count'];
                       },
                    ], 
                    ['attribute'=>'order_unit_money',
                       'header' => Yii::t('app', '费率（元/小时）'),
                    ], 
                    ['attribute'=>'order_money',
                       'header' => Yii::t('app', '订单总金额（元）'),
                    ], 
                    [
                       'header' => Yii::t('app', '优惠金额（元）'),
                        'content'=>function($model,$key,$index){
                                   return 0;
                       },
                    ],
                    [
                       'attribute'=>'finance_worker_order_income_discount_amount',
                       'header' => Yii::t('app', '用户支付金额（元）'),
                    ],
                    [
                       'attribute'=>'order_pay_money',
                       'header' => Yii::t('app', '阿姨结算金额（元）'),
                    ],
               ],
               'responsive'=>true,
               'hover'=>true,
               'condensed'=>true,
               'floatHeader'=>true,
              'panel' => [
                    'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> 现金订单明细 </h3>',
                ],
           ]); Pjax::end(); 
           echo '</div>';
           
            ?>
        
<?php 
            echo '<div id = "taskInfo" style = "display:none">';
            Pjax::begin(); echo GridView::widget([
               'dataProvider' => $taskDataProvider,
               'columns' => [
                   ['class' => 'yii\grid\SerialColumn'],
                    ['attribute'=>'finance_worker_non_order_income_name',
                       'header' => Yii::t('app', '任务名称'),],
                    ['attribute'=>'finance_worker_non_order_income_des',
                       'header' => Yii::t('app', '任务说明'),],
                    [
                       'header' => Yii::t('app', '完成时间'),
                        'attribute' => 'finance_worker_non_order_income_complete_time',
                    ],
                    ['attribute'=>'finance_worker_non_order_income',
                       'header' => Yii::t('app', '奖励（元）'),
                    ], 
               ],
               'responsive'=>true,
               'hover'=>true,
               'condensed'=>true,
               'floatHeader'=>true,
              'panel' => [
                    'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> 奖励明细 </h3>',
                ],
           ]); Pjax::end(); 
           echo '</div>';
           
            ?>
    <?php 
            echo '<div id = "compensateInfo" style = "display:none">';
            Pjax::begin(); echo GridView::widget([
               'dataProvider' => $compensateDataProvider,
               'columns' => [
                   ['class' => 'yii\grid\SerialColumn'],
                    ['attribute'=>'id',
                       'header' => Yii::t('app', '赔偿编号'),],
                    ['attribute'=>'finance_compensate_oa_code',
                       'header' => Yii::t('app', 'OA审批号'),],
                    [
                       'header' => Yii::t('app', '投诉编号'),
                        'attribute' => 'finance_complaint_id',
                    ],
                    ['attribute'=>'order_id',
                       'header' => Yii::t('app', '订单编号'),
                    ], 
                   ['attribute'=>'finance_compensate_reason',
                       'header' => Yii::t('app', '赔偿原因'),
                    ], 
                   ['attribute'=>'finance_compensate_total_money',
                       'header' => Yii::t('app', '赔偿总金额'),
                    ], 
                   ['attribute'=>'finance_compensate_insurance_money',
                       'header' => Yii::t('app', '保险赔付金额'),
                    ], 
                   ['attribute'=>'finance_compensate_company_money',
                       'header' => Yii::t('app', '公司赔付金额'),
                    ], 
                   ['attribute'=>'finance_compensate_worker_money',
                       'header' => Yii::t('app', '阿姨赔付金额'),
                    ], 
               ],
               'responsive'=>true,
               'hover'=>true,
               'condensed'=>true,
               'floatHeader'=>true,
              'panel' => [
                    'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> 赔偿扣款 </h3>',
                ],
           ]); Pjax::end(); 
           echo '</div>';
           
            ?>

 <?php 
         
            $js=<<<JS
                    $(".ordercount").click(function(){
                        $("#allOrderInfo").css('display','block');
                        $("#cashOrderInfo").css('display','none');
                        $("#taskInfo").css('display','none');
                        $("#compensateInfo").css('display','none');
                    });
                    $(".cashordercount").click(function(){
                        $("#allOrderInfo").css('display','none');
                        $("#cashOrderInfo").css('display','block');
                        $("#taskInfo").css('display','none');
                        $("#compensateInfo").css('display','none');
                    });
                    $(".noncashordercount").click(function(){
                        $("#allOrderInfo").css('display','none');
                        $("#cashOrderInfo").css('display','none');
                        $("#taskInfo").css('display','none');
                        $("#compensateInfo").css('display','none');
                    }); 
                    $(".taskcount").click(function(){
                        $("#allOrderInfo").css('display','none');
                        $("#cashOrderInfo").css('display','none');
                        $("#taskInfo").css('display','block');
                        $("#compensateInfo").css('display','none');
                    });
                    $(".deductionmoney").click(function(){
                        $("#allOrderInfo").css('display','none');
                        $("#cashOrderInfo").css('display','none');
                        $("#taskInfo").css('display','none');
                        $("#compensateInfo").css('display','block');
                    });
JS;
        $this->registerJs(
                $js
        );
         ?>
