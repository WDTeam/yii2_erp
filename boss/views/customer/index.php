<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use common\models\CustomerPlatform;
use common\models\CustomerChannal;
use common\models\CustomerAddress;

use common\models\GeneralRegion;
use common\models\Order;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */

$this->title = Yii::t('app', '顾客管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php //echo Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Worker',]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format' => 'raw',
                'label' => 'ID',
                'value' => function ($dataProvider) {
                    return '<a href="">'.$dataProvider->id.'</a>';
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '姓名',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_name;
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '电话',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_phone;
                },
                'width' => "100px",
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
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '身份',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_is_vip ? '会员' : '非会员';
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '平台',
                'value' => function ($dataProvider) {
                    $platform = CustomerPlatform::find()->where(['id'=>$dataProvider->platform_id])->one();
                    return $platform->platform_name ? $platform->platform_name : '-';
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '渠道',
                'value' => function ($dataProvider) {
                    $channal = CustomerChannal::find()->where(['id'=>$dataProvider->channal_id])->one();
                    return $channal->channal_name ? $channal->channal_name : '-';
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '订单',
                'value' => function ($dataProvider) {
                    $order_count = order::find()->where(['customer_id'=>$dataProvider->id])->count();
                    return $order_count;
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '余额',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_balance;
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '投诉',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_complaint_times;
                },
                'width' => "100px",
            ],
            [
                'format' => 'datetime',
                'label' => '创建时间',
                'value' => function ($dataProvider) {
                    return $dataProvider->created_at;
                },
                'width' => "100px",
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('加入黑名单', Yii::$app->urlManager->createUrl(['customer/add-to-block', 'id' => $model->id, 'edit' => 't']), [
                            'title' => Yii::t('yii', 'Edit'),
                        ]);
                    }
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
            'before' =>Html::a('<i class="glyphicon" ></i>黑名单', ['/customer/block?CustomerSearch[is_del]=1'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']),
            // 'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List',
            //     ['index'],
            //     ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]);
    Pjax::end(); ?>

</div>



