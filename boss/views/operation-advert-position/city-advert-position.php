<?php
use yii\helpers\Html;
use yii\grid\GridView;

use boss\components\SearchBox;
echo SearchBox::widget([
        'action' => ['index'],
        'method' => 'POST',
        'options' => [],
        'type' => 'Field',
        'keyword_value' => isset($params['keyword']) ? $params['keyword'] : '',
        'keyword_options' => ['placeholder' => '搜索关键字', 'class' => 'form-control'],
        'submit_options' => ['class' => 'btn btn-default form-control'],
        'fields' => ['搜索字段', 'province_name' => '省份名称', 'city_name' => '城市名称'],
        'default' => isset($params['fields']) ? $params['fields'] : '',
    ]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'header' => Yii::t('app', 'Order Number'),
            'class' => 'yii\grid\SerialColumn'
        ],
        [
            'header' => '位置名称',
            'attribute'=> 'operation_advert_position_name',
        ],
        [
            'header' => '平台名称',
            'attribute'=> 'operation_platform_name',
        ],
        [
            'header' => '版本名称',
            'attribute'=> 'operation_platform_version_name',
        ],
        [
            'header' => '宽度（像素）',
            'attribute'=> 'operation_advert_position_width',
        ],
        [
            'header' => '高度（像素）',
            'attribute'=> 'operation_advert_position_height',
        ],
//        'operation_advert_position_name',
//        'operation_platform_name',
//        'operation_platform_version_name',
//        'operation_advert_position_width',
//        'operation_advert_position_height',
        [
            'header' => Yii::t('app', 'Operation'),
            'class' => 'yii\grid\ActionColumn'
        ],
    ],
]);