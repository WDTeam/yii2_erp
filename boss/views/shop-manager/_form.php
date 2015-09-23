<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var boss\models\ShopManager $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-manager-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
    <h2>基础信息</h2>
<?php echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        
        'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 家政名称...', 'maxlength'=>255]], 
        
        'province_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 省份...', 'maxlength'=>50]], 
        
        'city_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 城市...', 'maxlength'=>50]], 
        
        'county_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 县...', 'maxlength'=>50]],
        
        'street'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 办公街道...', 'maxlength'=>255]], 
        
        'principal'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 负责人...', 'maxlength'=>50]], 
        
        'tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 电话...', 'maxlength'=>50]], 

        'other_contact'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 其他联系方式...', 'maxlength'=>200]],
        
        'is_blacklist'=>['type'=> Form::INPUT_TEXT, 'label'=>'是否黑名单', 'options'=>['placeholder'=>'Enter 是否是黑名单：0正常，1黑名单...']], 
        
        'audit_status'=>['type'=> Form::INPUT_TEXT, 'label'=>'审核状态', 'options'=>['placeholder'=>'Enter 审核状态：0未审核，1通过，2不通过...']], 
        
        'level'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 评级...', 'maxlength'=>50]],
        
    ]

    ]);?>
    <h2>营业执照息</h2>
    <?php echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

        'bl_name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 营业执照名称...', 'maxlength'=>255]],
        
        'bl_address'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 营业地址...', 'maxlength'=>255]],
        
        'bl_photo_url'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 营业执照URL...', 'maxlength'=>255]],
        
        'blacklist_cause'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 黑名单原因...', 'maxlength'=>255]],
        
        'bl_person'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 法人代表...', 'maxlength'=>50]],
        
        'bl_number'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 注册号...', 'maxlength'=>200]],
        
        'bl_create_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 注册时间...']],
        
        'bl_expiry_start'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 有效期起始时间...']],
        
        'bl_expiry_end'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 有效期结束时间...']],

        'bl_audit'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 注册资本...']],
        
        'bl_business'=>['type'=> Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter 营业范围...','rows'=> 6]],
        
    ]

    ]);?>
    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
