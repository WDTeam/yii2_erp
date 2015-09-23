<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */

$this->title = Yii::t('app', '顾客黑名单');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Worker',
]), ['create'], ['class' => 'btn btn-success'])*/ ?>
    </p>

    <?php Pjax::begin();
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'customer_name:text',
            'customer_phone:text',
            [
                'format' => 'raw',
                'label' => '住址',
                'value' => function ($dataProvider) {
                    //return $dataProvider->platform_id ? '' : '非自有';
                    return '北京市朝阳区SOHO一期2单元';
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
                    return 'IOS';
                },
                'width' => "100px",
            ],
            [
                'format' => 'raw',
                'label' => '渠道',
                'value' => function ($dataProvider) {
                    return '美团';
                },
                'width' => "100px",
            ],
            'customer_score:integer',
            'customer_balance:decimal',
            'customer_complaint_times',
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('取消黑名单', Yii::$app->urlManager->createUrl(['customer/remove-from-block', 'id' => $model->id, 'edit' => 't']), [
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
            'before' =>Html::a('<i class="glyphicon" ></i>全部顾客', ['/customer/index?CustomerSearch[is_del]=0'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']),
            // 'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List',
            //     ['index'],
            //     ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]);
    Pjax::end(); ?>

</div>



