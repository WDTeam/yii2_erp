<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;

/**
 * @var yii\web\View $this
 * @var common\models\coupon\Coupon $model
 */

$this->title = '优惠券详情';
$this->params['breadcrumbs'][] = ['label' => Yii::t('boss', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-view">
<?php
echo DetailView::widget([
    'model' => $model,
    'condensed'=>false,
    'hover'=>true,
    'mode'=>DetailView::MODE_VIEW,
    'panel'=>[
        'heading'=>$this->title,
        'type'=>DetailView::TYPE_INFO,
    ],
    'attributes' => [
        [
            'attribute'=>'', 
            'label'=>'创建用户',
            'format'=>'raw',
            'value'=> \Yii::$app->user->identity->username,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
		[
            'attribute'=>'', 
            'label'=>'创建日期',
            'format'=>'raw',
            'value'=>date('Y-m-d H:i:s', $model->created_at),
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        [
            'attribute'=>'', 
            'label'=>'优惠码数量',
            'format'=>'raw',
            'value'=>$model->coupon_code_num,
            'type'=>DetailView::INPUT_TEXT,
            'valueColOptions'=>['style'=>'width:90%']
        ],
        //[
            //'attribute'=>'', 
            //'label'=>'单个优惠码最大使用人数',
            //'format'=>'raw',
            //'value'=>$model->coupon_code_max_customer_num,
            //'type'=>DetailView::INPUT_TEXT,
            //'valueColOptions'=>['style'=>'width:90%']
        //],
        //[
         //   'attribute'=>'', 
         //   'label'=>'投放总量',
         //   'format'=>'raw',
         //   'value'=>$model->coupon_code_num * $model->coupon_code_max_customer_num,
        //    'type'=>DetailView::INPUT_SWITCH,
        //    'valueColOptions'=>['style'=>'width:90%']
        //],
    ],
    'enableEditMode'=>false,
]); 





$couponCodeProvider = new ActiveDataProvider(['query' => \core\models\operation\coupon\CouponCode::find()->where(['coupon_id'=>$model->id])]);
echo GridView::widget([
    'dataProvider' => $couponCodeProvider,
    // 'responsive' => false,
    // 'hover' => false,
    // 'condensed' => false,
    // 'floatHeader' => false,
    // 'panel' => [
    //     'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i>历史状态信息</h3>',
    //     'type' => 'info',
    //     'before' =>'',
    //     'after' =>'',
    //     'showFooter' => false
    // ],
    'columns'=>[
        [
            'format' => 'raw',
            'label' => '优惠码',
            'value' => function ($couponCodeProvider) {
                return $couponCodeProvider->coupon_code;
            },
            'width' => "80px",
        ],
    ],
]);

?>
</div>
