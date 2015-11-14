<?php
use yii\helpers\Html;

use kartik\detail\DetailView;

use core\models\Customer\Customer;
use core\models\system\SystemUser;


use dbbase\models\finance\FinancePopOrder;
/**
 * @var yii\web\View $this
 * @var dbbase\models\FinancePopOrder $model
 */

$this->title = '订单号为:'.$model->finance_pop_order_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '账单详情'), 'url' => ['index']];
$this->params['breadcrumbs'][] =$this->title;

$userinfo=Customer::getCustomerById($model->finance_pop_order_worker_uid);

if(isset($userinfo->customer_name)){$username=$userinfo->customer_name;}else{$username='未查到';}

$admininfo=SystemUser::findIdentity($model->finance_pop_order_check_id);

if($admininfo){$adminname=$admininfo->username;}else{$adminname='未查到';}

$channel_title=\core\models\operation\OperationOrderChannel::get_post_name($model->finance_order_channel_id);

?>
<div class="finance-pop-order-view">
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
    		'buttons1'=>'<a href="javascript:history.go(-1)">返回</a>',
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [

            //'id',
    		/* [
    		'attribute' => 'finance_pop_order_no',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>'坏账',
    		], */
            'finance_pop_order_number',
    		[
    		'attribute' => 'finance_order_channel_title',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>$channel_title,
    		], 
    		[
    		'attribute' => 'finance_pay_channel_title',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>\core\models\operation\OperationPayChannel::get_post_name($model->finance_pay_channel_id) ,
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
    		[
    		'attribute' => 'finance_pop_order_status',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'format'=>'raw',
    		'value'=>FinancePopOrder::get_pay_status($model->finance_pop_order_status),
    		],
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
    		'attribute' => 'finance_pop_order_msg',
    		'type' => DetailView::INPUT_TEXT,
    		'format'=>'raw',
    		'displayOnly' => true,
    		'value'=>'<font color="red">'.$model->finance_pop_order_msg?$model->finance_pop_order_msg:'暂无'.'</font>',
    		],
    		'finance_pop_order_code',
    		[
    		'attribute' => 'finance_pop_order_info_msg',
    		'type' => DetailView::INPUT_TEXT,
    		'displayOnly' => true,
    		'value'=>'(需要洪优提供接口)--订单提交成功--订单系统确认--订单正在分发中--订单分发到*阿姨--阿姨已确定--阿姨已上门--阿姨开始服务--订单完成--用户已评价',
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
