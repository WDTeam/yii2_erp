<?php
use yii\helpers\Html;
use yii\grid\GridView;

use boss\components\SearchBox;

echo SearchBox::widget([
        'action' => ['city-advert-position'],
        'method' => 'POST',
        'options' => [],
        'type' => 'Field',
        'keyword_value' => isset($params['keyword']) ? $params['keyword'] : '',
        'keyword_options' => ['placeholder' => '搜索关键字', 'class' => 'form-control'],
        'submit_options' => ['class' => 'btn btn-default', 'id' => 'ajax_submit_search'],
        'fields' => ['搜索字段', 'operation_advert_position_name' => '位置名称', 'operation_platform_name' => '平台名称', 'operation_platform_version_name' => '版本名称'],
        'default' => isset($params['fields']) ? $params['fields'] : '',
        'is_ajax_search' => true,
        'addons' => ['operation_city_id' => isset($p['operation_city_id']) ? $p['operation_city_id'] : 'all'],
        'callback' => 'adPositionShowHtml'
    ]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['header' => Yii::t('app', 'Order Number'), 'class' => 'yii\grid\SerialColumn'],
        ['header' => '位置名称', 'attribute'=> 'operation_advert_position_name'],
        ['header' => '平台名称', 'attribute'=> 'operation_platform_name'],
        ['header' => '版本名称', 'attribute'=> 'operation_platform_version_name'],
        ['header' => '宽度（像素）', 'attribute'=> 'operation_advert_position_width'],
        ['header' => '高度（像素）', 'attribute'=> 'operation_advert_position_height'],
        [
            'header' => Yii::t('app', 'Operation'), 'class' => 'yii\grid\ActionColumn',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open"></span>', 
                        'javascript:void(0);',
                        //Yii::$app->urlManager->createUrl(['operation-advert-position/view','id' => $model->id]),
                        ['title' => Yii::t('yii', 'View'), 'id' => '', 'class' => 'btn btn-success btn-sm', 'url' => 'operation-advert-position/view?id='.$model->id]
                    );
                },
                'update' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        'javascript:void(0);',
//                        Yii::$app->urlManager->createUrl(['operation-advert-position/update','id' => $model->id]),
                        ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-info btn-sm', 'url' => 'operation-advert-position/update?id='.$model->id]
                    );
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-trash"></span>', 
                        'javascript:void(0);',
//                        Yii::$app->urlManager->createUrl(['operation-advert-position/delete','id' => $model->id]),
                        ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'url' => 'operation-advert-position/delete?id='.$model->id, 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                    );
                },
            ],
        ],
    ],
]);