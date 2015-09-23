<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */

$this->title = Yii::t('app', '顾客管理');
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


    // echo GridView::widget([
    // 'columns' => [
    //     [
    //         'attribute' => 'name',
    //         'format' => 'text'
    //     ],
    //     [
    //         'attribute' => 'birthday',
    //         'format' => ['date', 'php:Y-m-d']
    //     ],
    // ],


    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'customer_name:text',
            'customer_phone:text',
            'region_id',
            'customer_is_vip',
            'platform_id',
            'channal_id:',
            'customer_score:integer',
            'customer_balance:decimal',
            'customer_complaint_times',
            'created_at:datetime',
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



