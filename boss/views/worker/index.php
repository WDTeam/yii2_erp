<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */

$this->title = Yii::t('app', '阿姨管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="worker-index">
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
            'worker_name',
            'shop_id',
            'worker_phone',
            'worker_idcard',
//            'worker_password',
//            'worker_photo',
//            'worker_level',
//            'worker_auth_status',
//            'worker_ontrial_status',
//            'worker_onboard_status',
//            'worker_work_city',
//            'worker_work_area',
//            'worker_work_street',
//            'worker_work_lng',
//            'worker_work_lat',
//            'worker_type',
            'worker_rule_id',
//            'worker_is_block',
//            'worker_is_blacklist',
            'created_ad',
//            'updated_ad',
//            'isdel',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['worker/view', 'id' => $model->id, 'edit' => 't']), [
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
            'before' =>
                Html::a('<i class="glyphicon" ></i>待试验', ['index?WorkerSearch[worker_auth_status]=0'], ['class' => 'btn btn-success ', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>未培训', ['index?WorkerSearch[worker_ontrial_status]=0'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>试工', ['index?WorkerSearch[worker_onboard_status]=0'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>时段', ['index?WorkerSearch[worker_rule_id]=1'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>高峰', ['index?WorkerSearch[worker_rule_id]=2'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>全职', ['index?WorkerSearch[worker_rule_id]=3'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>请假', ['index?WorkerSearch[worker_rule_id]=4'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>封号', ['index?WorkerSearch[worker_is_block]=1'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']) .
                Html::a('<i class="glyphicon" ></i>黑名单', ['index?WorkerSearch[worker_is_blacklist]=1'], ['class' => 'btn btn-success', 'style' => 'margin-right:10px']),
            'after' => Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List',
                ['index'],
                ['class' => 'btn btn-info']),
            'showFooter' => false
        ],
    ]);
    Pjax::end(); ?>

</div>
