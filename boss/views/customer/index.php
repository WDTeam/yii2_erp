<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\Shop;
use yii\helpers\ArrayHelper;
use kartik\nav\NavX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;

use boss\components\AreaCascade;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\base\Widget;
use yii\widgets\ActiveForm;

use common\models\CustomerPlatform;
use common\models\CustomerChannal;
use common\models\CustomerAddress;
use common\models\GeneralRegion;
use common\models\OrderExtCustomer;
use common\models\CustomerExtBalance;
use common\models\CustomerExtSrc;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '客户管理');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 客户搜索</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    </div>
    <p>
        <?php //echo Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Worker',]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    $b= Html::a('<i class="glyphicon" ></i>全部 '.$searchModel->countALLCustomer(), ['index'], ['class' => 'btn btn-success-selected', 'style' => 'margin-right:10px']). 
    Html::a('<i class="glyphicon" ></i>黑名单 '.$searchModel->countBlockCustomer(), ['index?CustomerSearch[is_del]=1'], ['class' => 'btn btn-success-selected', 'style' => 'margin-right:10px']);
    
   
    ?>
    <?php 
    // ActiveForm::begin([
    // 'action' => ['multi-add-to-block'],
    // 'method' => 'post'
    //         ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'export'=>false,
        'toolbar' =>
            [
                'content'=>
                    // Html::submitButton(Yii::t('app', '批量 '), ['class' => 'btn btn-default','style' => 'margin-right:10px','data-toggle'=>'modal',
                    //     'data-target'=>'#multi-add-to-block-modal',]),
                    // Html::a('<i class="glyphicon">批量加入黑名单</i>', ['customer/multi-add-to-block'], [
                    //     'class' => 'btn btn-default multi-add-to-block',
                    //     'data-toggle'=>'modal',
                    //     'data-target'=>'#multi-add-to-block-modal',
                    // ]),
                    // Html::a('<i class="glyphicon">批量移除黑名单</i>', ['customer/multi-remove-from-block'], [
                    //     'class' => 'btn btn-default multi-remove-from-block',
                    // ]),
                    Html::a('<i class="glyphicon">导入测试数据</i>', ['customer/data'], [
                        'class' => 'btn btn-default',
                    ]),
            ],
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            // [
            //     'format' => 'raw',
            //     'label' => 'ID',
            //     'value' => function ($dataProvider) {
            //         return $dataProvider->id;
            //     },
            //     'width' => "0px",
            // ],
            [
                'class' => 'yii\grid\CheckboxColumn',
                
            ],

            // [
            //     'format' => 'raw',
            //     'label' => '姓名',
            //     'value' => function ($dataProvider) {
            //         $name_show = empty($dataProvider->customer_name) ? "未知" : $dataProvider->customer_name;
            //         return '<a href="/customer/' . $dataProvider->id . '">'.$name_show.'</a>';
            //     },
            //     'width' => "80px",
            // ],
            [
                'format' => 'raw',
                'label' => '状态',
                'value' => function ($dataProvider) {
                    $currentBlockStatus = \core\models\customer\CustomerBlockLog::getCurrentBlockStatus($dataProvider->id);
                    return $currentBlockStatus['block_status_name'];
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '电话',
                'value' => function ($dataProvider) {
                    // return '<a href="/customer/' . $dataProvider->id . '">'.$dataProvider->customer_phone.'</a>';
                    return Html::a('<i class="glyphicon">'.$dataProvider->customer_phone.'</i>', ['customer/view', 'id'=>$dataProvider->id]);
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '订单地址',
                'value' => function ($dataProvider) {
                    $address_count = CustomerAddress::find()->where([
                        'customer_id'=>$dataProvider->id,
                        ])->count();
                    $customer_address = CustomerAddress::find()->where([
                        'customer_id'=>$dataProvider->id,
                        'customer_address_status'=>1])->one();
                    
                    if ($customer_address) {
                        $general_region_id = $customer_address->general_region_id;
                        $general_region = GeneralRegion::find()->where([
                        'id'=>$general_region_id,
                        ])->one();
                        if ($address_count <= 0) {
                            return '-';
                        }
                        if ($address_count == 1) {
                            return $general_region->general_region_province_name 
                            . $general_region->general_region_city_name 
                            . $general_region->general_region_area_name;
                        }
                        if ($address_count > 1) {
                            return $general_region->general_region_province_name 
                            . $general_region->general_region_city_name 
                            . $general_region->general_region_area_name
                            . '...';
                        }
                    }else{
                        return '-';
                    }
                    
                },
                'width' => "150px",
            ],
            [
                'format' => 'raw',
                'label' => '身份',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_is_vip ? '会员' : '非会员';
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '平台',
                'value' => function ($dataProvider) {
                    // $platform = CustomerPlatform::find()->where(['id'=>$dataProvider->platform_id])->one();
                    // return $platform ? $platform->platform_name : '-';
                    $customerExtSrc = CustomerExtSrc::find()->where(['customer_id'=>$dataProvider->id])->orderBy('created_at asc')->one();
                    return $customerExtSrc == NULL ? '-' : $customerExtSrc->platform_name;
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '渠道',
                'value' => function ($dataProvider) {
                    // $channal = CustomerChannal::find()->where(['id'=>$dataProvider->channal_id])->one();
                    // return $channal ? $channal->channal_name : '-';
                    $customerExtSrc = CustomerExtSrc::find()->where(['customer_id'=>$dataProvider->id])->orderBy('created_at asc')->one();
                    return $customerExtSrc == NULL ? '-' : $customerExtSrc->platform_name;
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '订单',
                'value' => function ($dataProvider) {
                    $order_count = OrderExtCustomer::find()->where(['customer_id'=>$dataProvider->id])->count();
                    return '<a href="/order/index?OrderSearch[customer_id]='. $dataProvider->id .'">'.$order_count.'</a>';
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '余额',
                'value' => function ($dataProvider) {
                    $customerExtBalance = CustomerExtBalance::findOne($dataProvider->id);
                    $customer_balance = $customerExtBalance != NULL ? $customerExtBalance->customer_balance : 0;
                    return '￥'.$customer_balance;
                },
                'width' => "80px",
            ],
            [
                'format' => 'raw',
                'label' => '投诉',
                'value' => function ($dataProvider) {
                    return '<a href="/order/index?OrderSearch[customer_id]='. $dataProvider->id .'">' . $dataProvider->customer_complaint_times . '</a>';
                },
                'width' => "80px",
            ],
            [
                'format' => 'datetime',
                'label' => '创建时间',
                'value' => function ($dataProvider) {
                    return $dataProvider->created_at;
                    
                },
                'width' => "160px",
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{block}',
                'buttons' => [
                    'block' => function ($url, $model) {
                        return empty($model->is_del) ? Html::a('加入黑名单', [
                            'customer/add-to-block',
                            'id' => $model->id
                        ], [
                            'title' => Yii::t('app', '加入黑名单'),
                            'data-toggle'=>'modal',
                            'data-target'=>'#modal',
                            'data-id'=>$model->id,
                            'class'=>'block-btn',
                        ]) : Html::a('解除黑名单', [
                            'customer/remove-from-block',
                            'id' => $model->id
                            
                        ], [
                            'title' => Yii::t('app', '解除黑名单'),
                        ]);
                    },
                ],
            ],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' =>$b,
            // 'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List',
            //     ['index'],
            //     ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
        
    ]);
    // ActiveForm::end(); 
    ?>
</div>
<?php echo Modal::widget([
'header' => '<h4 class="modal-title">黑名单原因</h4>',
'id' =>'modal',
]);?>

<?php $this->registerJs(<<<JSCONTENT
$('.block-btn').click(function(){
    $('#modal .modal-body').html('加载中……');
    $('#modal .modal-body').eq(0).load(this.href);
});
JSCONTENT
);?>
</div>





