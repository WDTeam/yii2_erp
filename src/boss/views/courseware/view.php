<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var common\models\Courseware $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => $category->catename, 'url' => ['category/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coursewares'), 'url' => ['index', 'classify_id' => $category->cateid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courseware-view">
<!--    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>-->


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
            'id',
            'image',
            'url:url',
            'name',
            //'pv',
            'order_number',
            'classify_id',
        ],
        'deleteOptions'=>[
            'url'=>['delete', 'id' => $model->id, 'classify_id' => $category->cateid],
//            'data'=>[
//                'confirm'=>Yii::t('app', '你确定要删除这个项目么?'),
//                'method'=>'post',
//            ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
