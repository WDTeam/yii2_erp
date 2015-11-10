<style>
    .col-md-2 {margin-top: 15px;}
</style>
<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('operation', 'Operation Shop Districts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Cities'), 'url' => ['/operation/operation-city/index']];
$this->params['breadcrumbs'][] = ['label' => $city_name];
$this->params['breadcrumbs'][] = $this->title;
?>
    <?php $form = ActiveForm::begin([
		'options' => ['enctype' => 'multipart/form-data', 'accept-charset' => 'UTF-8'],
        'action' => ['index', 'city_id' => $city_id, 'city_name' => $city_name],
        'method' => 'post',
    ]);
    ?>
    <div class='col-md-2'>
        <h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> <?=Html::encode($this->title)?><?=Html::encode($city_name)?> </h3>
    </div>
    <div class='col-md-3'>
        <?= $form->field($model, 'district_upload_url')->fileInput(['maxlength' => true]) ?>
    </div>
     
    <div class='col-md-2 form-inline'>
      <?= Html::submitButton(Yii::t('app', '提交'), ['class' => 'btn btn-primary']) ?>
    </div> 
    <?php ActiveForm::end(); ?>
<div class="operation-shop-district-index">


    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'operation_area_name',
            'operation_shop_district_name',
            [
                'header'=>"上线状态",
                'attribute'=> 'operation_shop_district_status',
                'format'=>'html',
                'value' => function ($model) use ($districtModel) {
                    $id = $districtModel::getShopDistrict($model->id);
                    if ($id > 0) {
                        return '已上线';
                    } elseif ($id == 0) {
                        return '未上线';
                    } else {
                        return '状态异常，请手动编辑状态';
                    }
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app', 'Operation'),
                'template' => '{update} {delete} {listbtn}',
                'buttons' => [
//                    'view' => function ($url, $model) {
//                        return Html::a(
//                            '<span class="glyphicon glyphicon-eye-open"></span>', 
//                            Yii::$app->urlManager->createUrl(['operation-shop-district/view','id' => $model->id]),
//                            ['title' => Yii::t('yii', 'View'), 'class' => 'btn btn-success btn-sm']
//                        );
//                    },
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('yii', '编辑'), ['/operation/operation-shop-district/update', 'id' => $model->id], ['class' => 'btn btn-info btn-sm']);
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-shop-district/update','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Update'), 'class' => 'btn btn-info btn-sm']
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('yii', '删除'), ['/operation/operation-shop-district/delete', 'id' => $model->id], ['class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"删除商圈将删除商圈下的服务项目，\n您确定要删除吗？", 'aria-label'=>Yii::t('yii', 'Delete')]);
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', 
                            Yii::$app->urlManager->createUrl(['/operation/operation-shop-district/delete','id' => $model->id]),
                            ['title' => Yii::t('yii', 'Delete'), 'class' => 'btn btn-danger btn-sm', 'data-pjax'=>"0", 'data-method'=>"post", 'data-confirm'=>"您确定要删除此项吗？", 'aria-label'=>Yii::t('yii', 'Delete')]
                        );
                    },      
                    'listbtn' => function ($url, $model) {
                        return '';
                        return Html::a('<span class="glyphicon glyphicon-list"></span>',Yii::$app->urlManager->createUrl(['/operation/operation-shop-district/goodslist','id' => $model->id]),['title' => Yii::t('yii', '商圈商品列表'), 'class' => 'btn btn-warning btn-sm']);
                    },
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,

        'panel' => [
            'heading'=>'',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> '.Yii::t('app', '增加商圈'), ['create', 'city_id' => $city_id, 'city_name' => $city_name], ['class' => 'btn btn-success']),
            //'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
