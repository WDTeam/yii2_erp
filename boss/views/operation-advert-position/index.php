<?php

use yii\helpers\Html;
use yii\grid\GridView;
use boss\components\SearchBox;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Advert Position');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-position-index">

<!--    <h1><?php //= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a(Yii::t('app', 'Create').Yii::t('app', 'Advert Position'), 'javascript:void(0);', ['class' => 'btn btn-success', 'id' => 'add-advert-position', 'url' => '/operation-advert-position/create']) ?>
    </p>
    <ul class="nav nav-tabs">
        <li id="tab_index" city_id="all" role="presentation" class="active"><a href="#">全部</a></li>
        <!--<li role="presentation"><a href="#">Profile</a></li>-->
        <li role="presentation" id="select-city-list">
            <div class="btn-group">
                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    更多城市… <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach($citys as $city){?>
                    <li city_id="<?php echo $city->city_id ?>"><a href="javascript:void(0);"><?php echo $city->city_name?></a></li>
                    <?php }?>
                </ul>
            </div>
        </li>
    </ul>
    <div class="container-fluid operation-panel" id="loadBox">
            <?=SearchBox::widget([
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
                'addons' => ['operation_city_id' => 'all'],
                'callback' => 'adPositionShowHtml'
            ]);?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['header' => Yii::t('app', 'Order Number'), 'class' => 'yii\grid\SerialColumn'],
                    ['header' => '所属城市', 'attribute'=> 'operation_city_name'],
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
                                    Yii::$app->urlManager->createUrl(['operation-advert-position/view','id' => $model->id]),
                                    ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
                                );
                            },
                            'update' => function ($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>', 
                                    Yii::$app->urlManager->createUrl(['operation-advert-position/update','id' => $model->id]),
                                    ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-info btn-sm']
                                );
                            },
                            'delete' => function ($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-trash"></span>', 
                                    Yii::$app->urlManager->createUrl(['operation-advert-position/delete','id' => $model->id]),
                                    ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                                );
                            },
                        ],
                    ],
                ],
            ]); ?>
    </div>
</div>
