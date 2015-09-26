<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Advert Position');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-advert-position-index">

<!--    <h1><?php //= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a(Yii::t('app', 'Create').Yii::t('app', 'Advert Position'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <ul class="nav nav-tabs">
        <li id="tab_index" role="presentation" class="active"><a href="#">全部</a></li>
        <!--<li role="presentation"><a href="#">Profile</a></li>-->
        <li role="presentation" id="select-city-list">
            <div class="btn-group">
                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    更多城市… <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <?php foreach($citys as $city){?>
                    <li city_id="<?php echo $city->id ?>"><a href="javascript:void(0);"><?php echo $city->city_name?></a></li>
                    <?php }?>
                </ul>
            </div>
        </li>
    </ul>
    <div class="container-fluid operation-panel">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'header' => Yii::t('app', 'Order Number'),
                        'class' => 'yii\grid\SerialColumn'
                    ],

        //            'id',
                    'operation_advert_position_name',
                    'operation_platform_name',
                    'operation_platform_version_name',
                    // 'operation_city_id',
        //            'operation_city_name',
                    'operation_advert_position_width',
                    'operation_advert_position_height',
                    // 'created_at',
                    // 'updated_at',

                    [
                        'header' => Yii::t('app', 'Operation'),
                        'class' => 'yii\grid\ActionColumn'
                    ],
                ],
            ]); ?>
    </div>
</div>
