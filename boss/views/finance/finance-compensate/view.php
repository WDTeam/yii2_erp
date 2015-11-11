<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dbbase\models\finance\FinanceCompensate $model
 */

$this->title = '赔偿详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Finance Compensates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-compensate-view">
  <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">赔偿详细信息</h3>
        </div>
        <div class="panel-body">
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'readonly'=>true,]); 
        ?>
            <?= $form->field($model, 'finance_compensate_oa_code'); ?>
            <?php 
//                echo $form->field($model, 'finance_compensate_money'); 
            ?>
<!--            <div class="form-group compensateCouponList">
                <label class="control-label col-md-2" for="financecompensate-finance_compensate_coupon"> 优惠券</label>-->
                <?php 
//                    $finance_compensate_coupon =  $model->finance_compensate_coupon;
//                    $finance_compensate_coupon_money =  $model->finance_compensate_coupon_money;
//                    $finance_compensate_coupon_arr = explode(";", $finance_compensate_coupon);
//                    $finance_compensate_coupon_money_arr = explode(";", $finance_compensate_coupon_money);
//                    $i = 0;
//                    foreach($finance_compensate_coupon_arr as $key => $value){
//                        echo '<div class="col-md-10"><input type="text" class="form-control" value = "'.$value.'"style="width:500px;height:35px;"   name="finance_compensate_coupon[]" id = "finance_compensate_coupon" readonly>&nbsp;&nbsp;<label style="color: #F00;" class = "coupon_money">金额：'.$finance_compensate_coupon_money_arr[$i].'</label></div>';
//                        $i++;
//                    }
                ?>
            <!--</div>-->
            <?= $form->field($model, 'finance_compensate_total_money'); ?>
            <?= $form->field($model, 'finance_compensate_insurance_money'); ?>
            <?= $form->field($model, 'finance_compensate_company_money'); ?>
            <?= $form->field($model, 'finance_compensate_worker_money'); ?>
            <?= $form->field($model, 'finance_compensate_reason'); ?>
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
