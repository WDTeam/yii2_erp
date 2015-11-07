<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\search\SystemUserSearch $searchModel
 */

$this->title = Yii::t('app', 'System Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-user-index">

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
            'username',
//             'auth_key',
//             'password_hash',
//             'password_reset_token',
           'email:email', 
           [
               'attribute'=>'roles',
               'value'=>function ($model){
                   return implode(',', $model->getRolesLabel());
               }
           ], 
           [
               'attribute'=>'status',
               'value'=>function ($model){
                   return $model->getStatusLabel();
               }
           ], 
//            'created_at', 
//            'updated_at', 

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', [
                            'view','id' => $model->id,'edit'=>'t'
                        ], [
                            'title' => Yii::t('yii', 'Edit'),
                        ]);
                    }
                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,

        'toolbar' =>[
            'content'=>Html::a('<i class="glyphicon glyphicon-plus"></i>', [
                'create'
            ], [
                'class' => 'btn btn-default',
                'title' => Yii::t('app', '添加系统用户')
            ]),
        ],


        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>'',      
            'after'=>false,
            'showFooter'=>false
        ],
    ]); Pjax::end(); ?>

</div>
