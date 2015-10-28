<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use core\models\operation\coupon\Coupon;
use core\models\operation\coupon\CouponCode;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\CouponSearch $searchModel
 */

$this->title = Yii::t('boss', '优惠券管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-index">
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i>优惠券搜索</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    </div>

    <p>
        <?php /* echo Html::a(Yii::t('boss', 'Create {modelClass}', [
    'modelClass' => 'Coupon',
]), ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'export'=>false,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'toolbar' =>
            [
                'content'=>'',
            ],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
			['class' => 'yii\grid\CheckboxColumn',],

            //'id',
            //'coupon_name',
			[
                'format' => 'raw',
                'label' => '优惠券名称',
                'value' => function ($dataProvider) {
                    return $dataProvider->coupon_name == '' ? '-' : $dataProvider->coupon_name;
                },
                'width' => "120px",
            ],
			[
                'format' => 'raw',
                'label' => '优惠码',
                'value' => function ($dataProvider) {
                    $coupon_code = CouponCode::getCouponCode($dataProvider->id);
					if($coupon_code == false){
						return '-';
					}else{
						$coupon_code_str = '';
						foreach($coupon_code as $key => $couponCode){
							if($couponCode != NULL){
								if ($key == 0)
								{
									$coupon_code_str .= $couponCode->coupon_code;
									
								}else{
	
									$coupon_code_str .= ' | '.$couponCode->coupon_code;
								}
							}
						}
						return $coupon_code_str;
					}
                },
                'width' => "120px",
            ],
			[
                'format' => 'raw',
                'label' => '优惠券面值',
                'value' => function ($dataProvider) {
                    return $dataProvider->coupon_price == '' ? '-' : $dataProvider->coupon_price;
                },
                'width' => "80px",
            ],
            //'coupon_type',
			
            //'coupon_type_name',
			[
                'format' => 'raw',
                'label' => '优惠券类型名称',
                'value' => function ($dataProvider) {
                    return $dataProvider->coupon_type_name == '' ? '-' : $dataProvider->coupon_type_name;
                },
                'width' => "80px",
            ],
//            'coupon_service_type_id', 
//            'coupon_service_type_name', 
//            'coupon_service_id', 
//            'coupon_service_name', 
			[
                'format' => 'raw',
                'label' => '服务',
                'value' => function ($dataProvider) {
                    $coupon_service_info = Coupon::getserviceInfo($dataProvider->id);
					switch ($coupon_service_info['coupon_type'])
					{
						case 0:
							# code...
							return '通用';
						break;
						case 1:
							# code...
							return $coupon_service_info['coupon_service_type_name'];
						break;
						case 2:
							# code...
							return $coupon_service_info['coupon_service_name'];
						break;
					
						default:
							# code...
						break;
					}
                },
                'width' => "80px",
            ],
//            'coupon_city_limit', 
//            'coupon_city_id', 
//            'coupon_city_name', 
			[
                'format' => 'raw',
                'label' => '城市',
                'value' => function ($dataProvider) {
                    $coupon_city_info = Coupon::getCityInfo($dataProvider->id);
					switch ($coupon_city_info['coupon_city_limit'])
					{
						case 0:
							# code...
							return '通用';
						break;
						case 1:
							# code...
							return $coupon_city_info['coupon_city_name'];
						break;
						
					
						default:
							# code...
						break;
					}
                },
                'width' => "80px",
            ],
//            'coupon_customer_type', 
//            'coupon_customer_type_name', 

			[
                'format' => 'raw',
                'label' => '对象',
                'value' => function ($dataProvider) {
                    $coupon_customer_type_info = Coupon::getCustomerTypeInfo($dataProvider->id);
					return $coupon_customer_type_info['coupon_customer_type_name'];
                },
                'width' => "80px",
            ],
//            'coupon_time_type:datetime', 
//            'coupon_time_type_name', 
//            'coupon_begin_at', 
//            'coupon_end_at', 
//            'coupon_get_end_at', 
//            'coupon_use_end_days', 
//            'coupon_promote_type', 
//            'coupon_promote_type_name', 
//            'coupon_order_min_price', 
//            'coupon_code_num', 
//            'coupon_code_max_customer_num', 
//            'is_disabled', 
			[
                'format' => 'raw',
                'label' => '状态',
                'value' => function ($dataProvider) {
                    return $dataProvider->is_disabled ? '已禁用' : '已启用';
                },
                'width' => "80px",
            ],
//            'created_at', 
//            'updated_at', 
//            'is_del', 
//            'system_user_id', 
//            'system_user_name', 

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{edit} | {disable} | {view} | {bind}',
                'buttons' => [
                    'edit' => function ($url, $model) {
                        return Html::a('编辑', [
                            'coupon/update',
                            'id' => $model->id
                        ], [
                            'title' => Yii::t('app', '编辑'),
                            'data-toggle'=>'modal',
                            'data-target'=>'#modal',
                            'data-id'=>$model->id,
                            'class'=>'block-btn',
                        ]);
                    },

					'disable' => function ($url, $model) {
						$disable_word = '';
						if ($model->is_disabled)
						{
							$disable_word = '启用';
							
						}else{
							$disable_word = '禁用';
						}
                        return Html::a($disable_word, [
                            'coupon/disable',
                            'id' => $model->id
                        ], [
                            'title' => Yii::t('app', $disable_word),
        
                            'class'=>'block-btn',
                        ]);
                    },

					'view' => function ($url, $model) {
                        return Html::a('详情', [
                            'operation/coupon/coupon/view',
                            'id' => $model->id
                        ], [
                            'title' => Yii::t('app', '详情'),
                            
                            'class'=>'block-btn',
                        ]);
                    },
                    'bind' => function ($url, $model) {
                        return Html::a('绑定', [
                            'bind',
                            'id' => $model->id
                        ], [
                            'title' => Yii::t('app', '绑定'),
                            'data-toggle'=>'modal',
                            'data-target'=>'#modal',
                            'data-id'=>$model->id,
                            'class'=>'bind-btn block-btn',
                        ]);
                    },
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'striped'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i>新增优惠券', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          				'after'=>'',
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
<?php echo Modal::widget([
    'header' => '<h4 class="modal-title">绑定手机号</h4>',
    'id' =>'modal',
]);?>
<?php $this->registerJs(<<<JSCONTENT
    $('.bind-btn').click(function(){
        $('#modal .modal-body').html('加载中……');
        $('#modal .modal-body').eq(0).load(this.href);
    });
JSCONTENT
);?>
