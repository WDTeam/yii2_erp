<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\CustomerCommentTagSearch $searchModel
 */

$this->title = Yii::t('boss', '评价标签管理');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-comment-tag-index">
     <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-upload"></i>标签查询</h3>
    </div>
    <div class="panel-body">
       <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    </div>
</div>
    
    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           // 'id',
            'customer_tag_name',
    		
    		[
    		'format' => 'raw',
    		'label' => '分类',
    		'value' => function ($dataProvider) {
    		if($dataProvider->customer_tag_type==1){
    			return  '评价';
    		}elseif ($dataProvider->customer_tag_type==2){
    			return '退款';
    		}else{
    			return '其他';
    		}
    		
    		
    		},
    		'width' => "100px",
    		],
			'customer_tag_count',
    		[
    		'format' => 'raw',
    		'label' => '评价等级',
    		'value' => function ($dataProvider) {
    		
    		if($dataProvider->customer_comment_level==1){
    			return  '满意';
    		}elseif ($dataProvider->customer_comment_level==2){
    			return '一般';
    		}else{
    			return '不满意';
    		}
    },
    		'width' => "100px",
    		],
            [
            'format' => 'raw',
            'label' => '状态',
            'value' => function ($dataProvider) {
            	return $dataProvider->is_online==1 ? '开启':'关闭';
            },
            'width' => "100px",
            ],
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' =>'{update} {delete}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['customer/customer-comment-tag/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', '修改'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
