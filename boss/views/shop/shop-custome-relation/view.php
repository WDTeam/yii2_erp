<?php
use core\models\system\SystemUser;
use core\models\shop\Shop;
use core\models\shop\ShopManager;
use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var dbbase\models\shop\ShopCustomeRelation $model
 */



$this->title = SystemUser::get_id_name($model->system_user_id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '修改用户关系'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-custome-relation-view">
    
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
           // 'id',
				[
				'attribute'=>'system_user_id', 
				'format'=>'raw',
				'value'=>$model->system_user_id,
				'type'=>DetailView::INPUT_DROPDOWN_LIST,
				'items'=>SystemUser::getuserlist(),
				'valueColOptions'=>['style'=>'width:30%']
				],
	    		
	    		[
	    		'attribute'=>'shop_manager_id',
	    		'format'=>'raw',
	    		'value'=>$model->shop_manager_id,
	    		'type'=>DetailView::INPUT_DROPDOWN_LIST,
	    		'items'=>ShopManager::ShowShopManager(),
	    		'valueColOptions'=>['style'=>'width:30%']
	    		], 
    		
    		[
    		'attribute'=>'shopid',
    		'format'=>'raw',
    		'value'=>$model->shopid,
    		'type'=>DetailView::INPUT_DROPDOWN_LIST,
    		'items'=>Shop::ShowShop(),
    		'valueColOptions'=>['style'=>'width:30%']
    		],
    		
    		
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
