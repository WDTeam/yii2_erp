<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\FinancePopOrderSearch $searchModel
 */

$this->title = Yii::t('app', '对账管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="finance-pop-order-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
   
    <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="finance-pop-order-search">
    <form id="w0" action="/finance-pop-order/index" method="get">
    <div class="form-group field-financepopordersearch-id">
<label class="control-label">对账名称</label>
<select>
<option value="1">美团团购</option>
<option value="2" >大众点评</option>
<option value="3" >京东到家</option>
</select>
<div class="help-block"></div>
</div>


<div class="form-group field-financepopordersearch-id">
<label class="control-label">支付渠道</label>

<select>
<option value="1">微信支付</option>
<option value="2" >支付宝支付</option>
<option value="3" >银联支付</option>
</select>

<div class="help-block"></div>
</div>

  <div class="form-group field-financepopordersearch-id">
<label class="control-label">上传第三方账单</label>
<input type="file" name="filename" />
<div class="help-block"></div>
</div>


</div>
    <p>
    
   <?php  echo Html::a(Yii::t('app', '{modelClass}', [
    'modelClass' => '提交',
]), ['create'], ['class' => 'btn btn-success'])  ?>
         </p> 
         </form>
          <p> 
           <?php  echo Html::a(Yii::t('app', '{modelClass}', [
    'modelClass' => '显示第三方成功订单',
]), ['create'], ['class' => 'btn btn-info'])  ?>
           
        <?php  echo Html::a(Yii::t('app', '{modelClass}', [
    'modelClass' => '显示第三方失败订单',
]), ['create'], ['class' => 'btn btn-success'])  ?>
        
        
        
 
    <?php Pjax::begin();
    
    
    //var_dump($dataProvider->getData()); exit;
    
    
     echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\CheckboxColumn'],
     		
            'id',
            'finance_pop_order_number',
            'finance_order_channel_id',
            'finance_order_channel_title',
            'finance_pay_channel_id',
            'finance_pay_channel_title', 
            'finance_pop_order_customer_tel', 
            'finance_pop_order_worker_uid', 
            'finance_pop_order_booked_time:datetime', 
            'finance_pop_order_booked_counttime:datetime', 
//            'finance_pop_order_sum_money', 
//            'finance_pop_order_coupon_count', 
//            'finance_pop_order_coupon_id', 
//            'finance_pop_order_order2', 
//            'finance_pop_order_channel_order', 
//            'finance_pop_order_order_type', 
//            'finance_pop_order_status', 
//            'finance_pop_order_finance_isok', 
//            'finance_pop_order_discount_pay', 
//            'finance_pop_order_reality_pay', 
//            'finance_pop_order_order_time:datetime', 
//            'finance_pop_order_pay_time:datetime', 
//            'finance_pop_order_pay_status', 
//            'finance_pop_order_pay_title', 
//            'finance_pop_order_check_id', 
//            'finance_pop_order_finance_time:datetime', 
//            'create_time:datetime', 
//            'is_del', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['finance-pop-order/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,


        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).'</h3>',
            'type'=>'primary',

           'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加', ['create'], ['class' => 'btn btn-success']),    
             // 'before'=>Html::a('<i class="glyphicon glyphicon-repeat"></i>批量处理', ['index'], ['class' => 'btn btn-info']),
                                                                                                                                        			'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 重置', ['index'], ['class' => 'btn btn-info']),
			
            'showFooter'=>false,
        ],
    ]); Pjax::end();

     ?>

</div>
