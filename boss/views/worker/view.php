<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;
use common\models\Shop;
use kartik\builder\TabularForm;
use kartik\widgets\SwitchInput;
use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2; // or kartik\select2\Select2
use kartik\tabs\TabsX;

use yii\helpers\ArrayHelper;
//var_dump($model->workertypeshow);
/**
 * @var yii\web\View $this
 * @var common\models\Worker $model
 */
$this->title = $workerModel->worker_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$content1 = $this->render('view_worker',['model'=>$workerModel]);
$content2 = $this->render('view_vacation',['workerVacationData'=>$workerVacationData]);
$content3 = $this->render('view_block',['workerModel'=>$workerModel,'workerBlockData'=>$workerBlockData]);
$content4 = $this->render('view_log',['workerBlockLogData'=>$workerBlockLogData]);
$items = [
    [
        'label'=>'<i class="glyphicon glyphicon-user"></i> 阿姨信息',
        'content'=>$content1,
        'active'=>true
    ],
    [
        'label'=>'<i class="fa  fa-fw fa-history"></i> 请假信息',
        'content'=>$content2,
        //'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/site/tabs-data'])],
        'active'=>false
    ],
    [
        'label'=>'<i class="fa fa-fw fa-lock"></i> 封号信息',
        'content'=>$content3,
        //'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/site/tabs-data'])],
        'active'=>false
    ],
    [
        'label'=>'<i class="fa fa-fw fa-book"></i> 操作记录',
        'content'=>$content4,
        //'linkOptions'=>['data-url'=>\yii\helpers\Url::to(['/site/tabs-data'])],
        'active'=>false
    ],
//    [
//        'label'=>'<i class="glyphicon glyphicon-list-alt"></i> Dropdown',
//        'items'=>[
//            [
//                'label'=>'<i class="glyphicon glyphicon-chevron-right"></i> Option 1',
//                'encode'=>false,
//                'content'=>$content3,
//            ],
//            [
//                'label'=>'<i class="glyphicon glyphicon-chevron-right"></i> Option 2',
//                'encode'=>false,
//                'content'=>$content4,
//            ],
//        ],
//    ],
//    [
//        'label'=>'<i class="glyphicon glyphicon-king"></i> Disabled',
//        'headerOptions' => ['class'=>'disabled']
//    ],
];
echo TabsX::widget([
    'items'=>$items,
    'position'=>TabsX::POS_ABOVE,
    'encodeLabels'=>false
]);



?>
