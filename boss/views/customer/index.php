<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\CustomerPlatform;
use common\models\CustomerChannal;

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
        <?php //echo Html::a(Yii::t('app', 'Create {modelClass}', ['modelClass' => 'Worker',]), ['create'], ['class' => 'btn btn-success']) 
        ?>
    </p>

    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'customer_name',
            'customer_phone',
            'customer_live_address_detail',
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
                'label' => '积分',
                'value' => function ($dataProvider) {
                    return $dataProvider->customer_score;
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



