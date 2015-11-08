<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $model backend\models\Admin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <?php
    $form = ActiveForm::begin([
        'enableClientValidation' => true,
        /*'enableAjaxValidation' => true,
        'validateOnSubmit'=>true,
        'validateOnChange' => false*/
    ]);
    ?>
    <div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
                <?= Yii::t('app', 'Role'); ?>
            </div>
			<div class="panel-body">
                <?php
                echo $form->field($model, 'name')->textInput($model->isNewRecord ? [] : ['disabled' => 'disabled']) .
                     $form->field($model, 'description')->textarea(['style' => 'height: 100px']) .
                     Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), [
                        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
                     ]);
                ?>
            </div>

		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-default">
			<div class="panel-heading">
                <?= Yii::t('app', 'Permissions'); ?>
            </div>

			<div class="panel-body">
            <?php 
                $groups = json_decode('{
                  "左侧菜单栏": [
                    "sidebar_customer", 
                    "sidebar_finance", 
                    "sidebar_housekeep",
                    "sidebar_operation",
                    "sidebar_order",
                    "sidebar_payment",
                    "sidebar_pop",
                    "sidebar_shop",
                    "sidebar_supplier",
                    "sidebar_worker"
                  ],
                  "家政管理": ["all_shopmanager_admin", "shopmanager/shop-manager/index", "shopmanager/shop-manager/create"],
                  "门店管理": ["all_shop_admin","shop/shop/index", "shop/shop/create"],
                  "阿姨管理": ["worker/worker/index", "worker/worker/create"],
                  "客户管理": ["customer/customer/index", "customer/customer-comment/index", "customer/customer-comment-tag/index"],
                  "订单管理": ["order/order/index", "order/order/create", "order/order/assign", "order/auto-assign/index", "order/order-complaint/index"],
                  "服务管理": ["operation/operation-category/index", "operation/operation-city/index", "operation/operation-city/opencity", "operation/operation-selected-service/index"],
                  "CMS管理": ["operation/operation-platform/index", "operation/operation-advert-position/index", "operation/operation-advert-content/index", "operation/operation-advert-release/index"],
                  "优惠券管理": ["operation/coupon/coupon/index", "operation/coupon/coupon/create"],
                  "运营管理": ["operation/operation-boot-page/index", "operation/worker-task/index"],
                  "服务卡管理": ["operation/operation-service-card-info/index", "operation/operation-service-card-sell-record/index", "operation/operation-service-card-with-customer/index", "operation/operation-service-card-consume-record/index"],
                  "对账管理": ["finance/finance-pay-channel/index", "finance/finance-header/index", "finance/finance-pop-order/index", "finance/finance-record-log/index", "finance/finance-pop-order/billinfo", "finance/finance-pop-order/bad"],
                  "结算管理": ["finance/finance-settle-apply/self-fulltime-worker-settle-index", "finance/finance-shop-settle-apply/index", "finance/finance-settle-apply/self-fulltime-worker-settle-index", "finance/finance-settle-apply/query", "finance/finance-shop-settle-apply/query"],
                  "退款管理": ["finance/finance-refund/index", "finance/finance-refund/countinfo"],
                  "赔偿管理": ["finance/finance-compensate/finance-confirm-index", "finance/finance-compensate/index"],
                  "报表管理": ["finance/finance-office-count/indexoffice"]
                }',true);
                foreach ($groups as $name=>$group){
                    $data = [];
                    foreach ($group as $key){
                        if(isset($permissions[$key])){
                            $data[$key] = $permissions[$key];
                            unset($permissions[$key]);
                        }
                    }
                    echo $form->field($model, 'permissions')->checkboxList($data, [
                        'id'=>'item_'.$name,
                    ])->label($name);
                }
                echo $form->field($model, 'permissions')->checkboxList($permissions, [
                    'id'=>'item_orther',
                ])->label('其它');
//                 echo $form->field($model, '_permissions')->checkboxList($permissions, ['class'=>'test'])->label('', ['hidden' => 'hidden']);
            ?>
            </div>
		</div>
	</div>
    <?php ActiveForm::end(); ?>
</div>
<?php $this->registerJs(<<<JSCONTENT
    $('.panel-body input[name="Auth[permissions]"]').remove();
JSCONTENT
);?>
