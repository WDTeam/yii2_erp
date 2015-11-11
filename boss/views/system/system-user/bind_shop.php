<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\base\Widget;

$this->title = Yii::t('app', '给用户绑定门店');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-user-bing-shop">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $model->username;?></h3>
        </div>
        <div class="panel-body">
            <div class="form-group field-shopmanager-name">
                <label>选择家政:</label>
                <?php echo Select2::widget([
                    'model'=>$model,
                    'attribute'=>'roles',
                    'data'=>$shop_managers,
                    'hideSearch'=>false,
                    'options'=>[
                        'multiple'=>true,
                        'placeholder'=>'请选择小家政……'
                    ],
                ]);?>
            </div>
            <div class="form-group field-shopmanager-name">
                <label>选择门店:</label>
                <?php echo Select2::widget([
                    'model'=>$model,
                    'attribute'=>'shopIds',
                    'data'=>$shops,
                    'hideSearch'=>false,
                    'options'=>[
                        'multiple'=>true,
                        'placeholder'=>'请选择门店……'
                    ],
                ]);?>
            </div>
        </div>
    </div>
    
</div>