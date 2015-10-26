<?php

use yii\helpers\Html;
use yii\grid\GridView;
use boss\components\SearchBox;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Advert Content');
//$this->params['breadcrumbs'][] = $this->title;
?>
<?=SearchBox::widget([
    'action' => ['ajax-list'],
    'method' => 'POST',
    'options' => ['class' => 'pull-right'],
    'type' => 'Field',
    'keyword_value' => isset($params['keyword']) ? $params['keyword'] : '',
    'keyword_options' => ['placeholder' => '搜索关键字', 'class' => 'form-control'],
    'submit_options' => ['class' => 'btn btn-default', 'id' => 'ajax_submit_search'],
    'fields' => ['搜索字段', 'operation_advert_content_name' => '广告内容标题', 'position_name' => '位置名称', 'platform_name' => '平台名称', 'platform_version_name' => '城市名称',],
    'default' => isset($params['fields']) ? $params['fields'] : '',
    'is_ajax_search' => true,
    'addons' => $post,
    'callback' => 'searchTable'
]);?>
<?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'header' => Yii::t('app', 'Order Number'),
                        'class' => 'yii\grid\SerialColumn'
                    ],

        //            'id',
                    [
                        'attribute'=> 'operation_advert_content_orders',
                        'format'=>'raw',
                        'value' => function ($model){
                            return Html::textInput('operation_advert_content_orders[]',$model->operation_advert_content_orders, ['class' => 'operation_advert_content_orders_input', 'content_id' => $model->id]);
                       }
                    ],
                    'operation_advert_content_name',
                    'position_name',
                    'platform_name',
                    'platform_version_name',
        //            'operation_advert_start_time:datetime',
        //             'operation_advert_end_time:datetime',
//                     'operation_advert_online_time:date',
//                     'operation_advert_offline_time:date',
                    [
                        'attribute'=> 'operation_advert_picture_text',
                        'format'=>'html',
                        'value' => function ($model){
                            if(empty($model->operation_advert_picture_text)){
                                return '';
                            }else{
                                return Html::a('<img border="0" src="'. $model->operation_advert_picture_text .'" height="30">',$model->operation_advert_url, ['target' => '_blank'] );
                            }
                       }
                    ],
//                     'operation_advert_url:url',
                     'created_at:datetime',
                     'updated_at:datetime',

                    [
                        'header' => Yii::t('app', 'Operation'),
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>', 
                                    Yii::$app->urlManager->createUrl(['operation-advert-content/view','id' => $model->id]),
                                    ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                                );
                            },
                            'update' => function ($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>', 
                                    Yii::$app->urlManager->createUrl(['operation-advert-content/update','id' => $model->id]),
                                    ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-info btn-sm']
                                );
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-trash"></span>', 
                                    Yii::$app->urlManager->createUrl(['operation-advert-content/delete','id' => $model->id]),
                                    ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                                );
                            },
                        ],
                    ],
                ],
            ]); ?>