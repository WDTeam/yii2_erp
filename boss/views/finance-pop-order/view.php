<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use common\models\FinanceOrderChannel;
use common\models\FinancePayChannel;
use core\models\Customer;
use common\models\SystemUser;
/**
 * @var yii\web\View $this
 * @var common\models\FinancePopOrder $model
 */

$this->title = '订单号为:'.$model->finance_pop_order_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '账单详情'), 'url' => ['index']];
$this->params['breadcrumbs'][] =$this->title;

$userinfo=Customer::getCustomerById($model->finance_pop_order_worker_uid);
if($userinfo){$uisername=$username->customer_name;}else{$username='未查到';}


//
$admininfo=SystemUser::findIdentity($model->finance_pop_order_worker_uid);
if($admininfo){$adminname=$admininfo->username;}else{$adminname='未查到';}
$order_channel_info=FinanceOrderChannel::get_order_channel_info($model->finance_order_channel_id);

?>
<div class="finance-pop-order-view">
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
            'finance_pop_order_number',
    		[
    		'attribute' => 'finance_order_channel_title',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=> $order_channel_info->finance_order_channel_name,
    		], 
    		[
    		'attribute' => 'finance_pay_channel_title',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=> FinancePayChannel::get_pay_channel_info($model->finance_pay_channel_id) ,
    		],
            'finance_pop_order_customer_tel',
    		[
    		'attribute' => 'finance_pop_order_worker_uid',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>$username,
    		],
            'finance_pop_order_booked_time:datetime',
            'finance_pop_order_booked_counttime:datetime',
            'finance_pop_order_sum_money',
            'finance_pop_order_coupon_count',
            'finance_pop_order_coupon_id',
            'finance_pop_order_order2',
            'finance_pop_order_channel_order',
            'finance_pop_order_order_type',
            'finance_pop_order_status',
           // 'finance_pop_order_finance_isok',
            'finance_pop_order_discount_pay',
            'finance_pop_order_reality_pay',
            'finance_pop_order_order_time:datetime',
            'finance_pop_order_pay_time:datetime',
    		
            'finance_pop_order_pay_status',
            'finance_pop_order_pay_title',
    		 [
    		'attribute' => 'finance_pop_order_check_id',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>$adminname,
    		],
            'finance_pop_order_finance_time:datetime',
            'create_time:datetime',
    		[
    		'attribute' => 'finance_pop_order_info_msg',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>'订单提交成功--订单系统确认--订单正在分发中--订单分发到*阿姨--阿姨已确定--阿姨已上门--阿姨开始服务--订单完成--用户已评价',
    		],
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', '你确定你要删除吗?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
