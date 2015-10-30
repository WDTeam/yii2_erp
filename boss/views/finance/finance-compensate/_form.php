<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dbbase\models\finance\FinanceCompensate $model
 * @var yii\widgets\ActiveForm $form
 */

?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><?= $this->title?></h3>
        </div>
        <div class="panel-body">
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); 
        ?>
            <?= $form->field($model, 'finance_compensate_oa_code'); ?>
            <?= $form->field($model, 'finance_compensate_money'); ?>
            <div class="form-group compensateCouponList">
                <label class="control-label col-md-2" for="financecompensate-finance_compensate_coupon"> 优惠券&nbsp;<span class="glyphicon glyphicon-plus add-compensate-coupon"></span></label>
                <?php 
                    $finance_compensate_coupon =  $model->finance_compensate_coupon;
                    $finance_compensate_coupon_money =  $model->finance_compensate_coupon_money;
                    if(!empty($finance_compensate_coupon)){
                        $finance_compensate_coupon_arr = explode(";", $finance_compensate_coupon);
                        $finance_compensate_coupon_money_arr = explode(";", $finance_compensate_coupon_money);
                        $i = 0;
                        foreach($finance_compensate_coupon_arr as $key => $value){
                            echo '<div class="col-md-10"><span class="glyphicon glyphicon-minus del-compensate-coupon"  style="display:inline"></span>&nbsp;<input type="text" class="finance_compensate_coupon" value = "'.$value.'" style="width:500px;height:35px;"   name="finance_compensate_coupon[]" id = "finance_compensate_coupon">&nbsp;&nbsp;<label style="color: #F00;" class = "coupon_money">金额：'.$finance_compensate_coupon_money_arr[$i].'</label></div>';
                            $i++;
                        }
                    }
                ?>
            </div>
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
        <div class="panel-body">
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

        <div class="panel-footer">
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-12">
                    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']); ?>
                </div>
            </div>
        </div>
<?php 
         
            $js=<<<JS
                    
                    $(".add-compensate-coupon").click(
                        function(){
                            var content = $('<div class="col-md-10"><span class="glyphicon glyphicon-minus del-compensate-coupon" style="display:inline"></span>&nbsp;<input type="text" class="finance_compensate_coupon" style="width:500px;height:35px;"   name="finance_compensate_coupon[]" id = "finance_compensate_coupon">&nbsp;&nbsp;<label style="display:none;color: #F00;" class = "coupon_money">金额：50</label></div>');
                            $('.compensateCouponList').append(content);
                            var label = $(content).find('.coupon_money');
                            content.find('input').change(function(){
                                    label.show();
                                });
                        }
                    ).click();
                    $(document).on("click",".del-compensate-coupon",function(){
                        $(this).parent().remove();
                    }); 
JS;
        $this->registerJs(
                $js
        );
         ?>