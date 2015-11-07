<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use \core\models\customer\Customer;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\CustomerCommentSearch $searchModel
 */

$this->title = Yii::t('boss', '评论管理');
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-comment-index">
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 评论搜索</h3>
    </div>
    <div class="panel-body">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    </div>
  
    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'export'=>false,
        'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
        'headerRowOptions'=>['class'=>'kartik-sheet-style'],
        'filterRowOptions'=>['class'=>'kartik-sheet-style'],
        'toolbar' =>
            [
                'content'=>'',
            ],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
            ],
            // ['class' => 'yii\grid\SerialColumn'],
    		'created_at:datetime',
    		
    		[
    		'format' => 'raw',
    		'label' => '城市',
    		'value' => function ($dataProvider) {
    		$cityname=\core\models\operation\OperationArea::getAreaname($dataProvider->city_id);
    		return  $cityname;
    		},
    		'width' => "100px",
    		],

    		[
    		'format' => 'raw',
    		'label' => '商圈地址',
    		'value' => function ($dataProvider) {
    		$Shopname=core\models\operation\OperationShopDistrict::getShopDistrictName($dataProvider->operation_shop_district_id);
				if(isset($Shopname)){
				return	$Shopname;
				}else{
					return	'无';
				}
    			
    			  
    		},
    		'width' => "100px",
    		],
    		'customer_comment_level_name',
    		'customer_comment_tag_names',
    		'customer_comment_content',
    		'order_id',
    		[
    		'format' => 'raw',
    		'label' => '客户',
    		'value' => function ($dataProvider) {
    			$info=core\models\customer\Customer::getCustomerById($dataProvider->customer_id);
    			if(count($info)>0){$name=$info['customer_name']; }else{ $name='暂无';}
    			return  $name;
    		},
    		'width' => "100px",
    		],
    		[
    		'format' => 'raw',
    		'label' => '阿姨',
    		'value' => function ($dataProvider) {
				$info=core\models\worker\Worker::getWorkerInfo($dataProvider->worker_id);
				if(count($info)>0){$name=$info['worker_name']; }else{ $name='暂无';}
    			return  $name;
    		},
    		'width' => "100px",
    		],
        ],
        'responsive' => true,
        'hover' => true,
        'condensed' => true,
        'floatHeader' => true,
        'striped'=>false,
        'panel' => [
            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Html::encode($this->title) . ' </h3>',
            'type' => 'info',
            'before' =>'',
            'after' =>'',
            'showFooter' => false
        ],
    ]);
    ?>
</div>
