<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\base\Widget;
use kartik\builder\Form;
use kartik\form\ActiveForm;

$this->title = Yii::t('app', '给用户绑定小家政');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-user-bing-shop">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $model->username;?></h3>
        </div>
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
        <div class="panel-body" style="padding:20px 30px;">
            <div class="form-group field-shopmanager-name">
                <label>选择门店:</label>
                <?php echo Select2::widget([
                    'model'=>$model,
                    'attribute'=>'shopManagerIds',
                    'data'=>$shop_managers,
                    'hideSearch'=>false,
                    'options'=>[
                        'multiple'=>true,
                        'placeholder'=>'请选择小家政……'
                    ],
                ]);?>
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-12">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']);?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>