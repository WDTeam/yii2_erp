<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use core\models\shop\ShopManager;
use boss\components\AreaCascade;

/**
 * @var yii\web\View $this
 * @var core\models\shop\ShopManager $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-manager-form">

    <?php $form = ActiveForm::begin([
        'type'=>ActiveForm::TYPE_HORIZONTAL,
        'options' =>  ['enctype' => 'multipart/form-data']
    ]); ?>
    <?php //var_dump($model->getErrors());?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">基础信息</h3>
        </div>
        <div class="panel-body">
        <?php echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                
                'name'=>[
                    'type'=> Form::INPUT_TEXT, 
                    'options'=>[
                        'maxlength'=>20,
                    ]
                    
                ],
//                 'account'=>[
//                     'type'=> Form::INPUT_TEXT,
//                 ],
//                 'password'=>[
//                     'label'=>'密码',
//                     'type'=> Form::INPUT_PASSWORD,
//                 ],
                
            ]
        ]);?>
        <?php 
        echo AreaCascade::widget([
            'model' => $model,
            'options' => ['class' => 'form-control inline'],
            'label' =>'选择城市',
            'grades' => 'county',
        ]);
        ?>
        <?php echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'street'=>['type'=> Form::INPUT_TEXT, 'options'=>[
                    'maxlength'=>255
                    
                ]], 
                
                'principal'=>['type'=> Form::INPUT_TEXT, 'options'=>[
                    'maxlength'=>50
                    
                ]], 
                
                'tel'=>['type'=> Form::INPUT_TEXT, 'options'=>[
                    'maxlength'=>11]], 
        
                'other_contact'=>['type'=> Form::INPUT_TEXT, 'options'=>[
                    'maxlength'=>200]],
            ]
        ]);?>
    
        <?php if(!$model->getIsNewRecord()){
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
    
                    'audit_status'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>ShopManager::$audit_statuses, 'label'=>'审核状态', 'options'=>['placeholder'=>'Enter 审核状态：0未审核，1通过，2不通过...']],
                    
                    'level'=>['type'=> Form::INPUT_TEXT, 'options'=>[
                        'maxlength'=>50]],
    
                    'blacklist_cause'=>['type'=> Form::INPUT_TEXT, 'options'=>[
                        'maxlength'=>255]],
                    
                    'is_blacklist'=>[
                        'type'=> Form::INPUT_RADIO_LIST, 
                        'items'=>['否', '是'], 
                        'label'=>'是否封号', 
                    ],
                ]
            
            ]);
        }?></div>

        <div class="panel-heading">
            <h3 class="panel-title">营业执照息</h3>
        </div>
        <div class="panel-body">
            <?php echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
        
                'bl_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['maxlength'=>20]],
                
                'bl_address'=>['type'=> Form::INPUT_TEXT, 'options'=>['maxlength'=>255]],
        
                'bl_type'=>[
                    'label'=>'注册类型',
                    'type'=> Form::INPUT_DROPDOWN_LIST, 
                    'items'=>ShopManager::$bl_types],
                
                'bl_person'=>['type'=> Form::INPUT_TEXT, 'options'=>['maxlength'=>10]],
                
                'bl_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['maxlength'=>200]],
                
                'bl_create_time'=>[
                    'type'=> Form::INPUT_WIDGET, 
                    'widgetClass'=>DateControl::classname(),
                    'options' => [
                        'type'=>DateControl::FORMAT_DATE,
                        'ajaxConversion'=>false,
                        'displayFormat' => 'php:Y-m-d',
                        'saveFormat'=>'php:U',
                        'options' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ]
                ],
                
                'bl_expiry_start'=>[
                    'type'=> Form::INPUT_WIDGET, 
                    'widgetClass'=>DateControl::classname(),
                    'options' => [
                        'type'=>DateControl::FORMAT_DATE,
                        'ajaxConversion'=>false,
                        'displayFormat' => 'php:Y-m-d',
                        'saveFormat'=>'php:U',
                        'options' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ]
                ],
                
                'bl_expiry_end'=>[
                    'type'=> Form::INPUT_WIDGET, 
                    'widgetClass'=>DateControl::classname(),
                    'options' => [
                        'type'=>DateControl::FORMAT_DATE,
                        'ajaxConversion'=>false,
                        'displayFormat' => 'php:Y-m-d',
                        'saveFormat'=>'php:U',
                        'options' => [
                            'pluginOptions' => [
                                'autoclose' => true
                            ]
                        ]
                    ]
                ],
        
                'bl_audit'=>['type'=> Form::INPUT_TEXT, 'options'=>[]],

                'bl_photo_url'=>[
                    'type'=> Form::INPUT_FILE,
                    'options'=>[ 'maxlength'=>255]
                ],
                
                'bl_business'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['rows'=> 6],],
                
            ]
            ]);?>

        </div>
        <div class="panel-footer">
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-12">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']);?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
