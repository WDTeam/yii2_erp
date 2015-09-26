<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\models\Shop;
use kartik\widgets\Affix;
use boss\components\AreaCascade;


// The widget
use kartik\widgets\Select2; // or kartik\select2\Select2
use yii\web\JsExpression;
use common\components\BankHelper;
use yii\helpers\Url

/**
 * @var yii\web\View $this
 * @var boss\models\Shop $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="shop-form">

<?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">基础信息</h3>
        </div>
        <div class="panel-body">
        <?php 
        $url = Url::to(['shop-manager/search-by-name']);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                
                'name'=>['type'=> Form::INPUT_TEXT, 'options'=>[ 'maxlength'=>100]], 
                
                'shop_manager_id'=>[
                    'label'=>'归属家政',
                    'type'=> Form::INPUT_WIDGET, 
                    'widgetClass'=>Select2::classname(),
                    'options'=>[
                        'initValueText' => '', // set the initial display text
                        'options' => ['placeholder' => 'Search for a shop_menager ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 0,
                            'ajax' => [
                                'url' => $url,
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {name:params.term}; }')
                            ],
        //                     'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(model) { return model.name; }'),
                            'templateSelection' => new JsExpression('function (model) { return model.name; }'),
                        ],
                    ]
                ], 
                
                'street'=>['type'=> Form::INPUT_TEXT, 'options'=>[ 'maxlength'=>255]], 
                
                'principal'=>['type'=> Form::INPUT_TEXT, 'options'=>[ 'maxlength'=>50]], 
                
                'tel'=>['type'=> Form::INPUT_TEXT, 'options'=>['maxlength'=>50]], 
                
                'other_contact'=>['type'=> Form::INPUT_TEXT, 'options'=>[ 'maxlength'=>200]],
                
                // 'create_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Create At...']], 
                
                // 'update_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Update At...']], 
        
        //         'blacklist_time'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 加入黑名单时间...']], 
        
                // 'worker_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 阿姨数量...']], 
                
                // 'complain_coutn'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 投诉数量...']], 
        
            ]
        ]);?>
        <?php if(!$model->getIsNewRecord()){
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
    
                    'audit_status'=>['type'=> Form::INPUT_DROPDOWN_LIST, 'items'=>Shop::$audit_statuses, 'label'=>'审核状态', 'options'=>['placeholder'=>'Enter 审核状态：0未审核，1通过，2不通过...']],
                    
                    'level'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 评级...', 'maxlength'=>50]],
            
                    'is_blacklist'=>['type'=> Form::INPUT_RADIO_LIST, 'items'=>['否', '是'], 'label'=>'是否黑名单', 'options'=>['placeholder'=>'Enter 是否是黑名单：0正常，1黑名单...']],
    
                    'blacklist_cause'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 黑名单原因...', 'maxlength'=>255]],
                    
                ]
            
            ]);
        }?>
        <?php 
        echo AreaCascade::widget([
            'model' => $model,
            'options' => ['class' => 'form-control inline'],
            'label' =>'选择城市',
            'grades' => 'county',
        ]);
        ?>
        </div>

        <div class="panel-heading">
            <h3 class="panel-title">银行信息</h3>
        </div>
        <div class="panel-body">
            <?php echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 1,
                'attributes' => [
                    
                    'opening_bank'=>[
                        'type'=> Form::INPUT_WIDGET,
                        'widgetClass'=>\kartik\widgets\Select2::className(),
                        'options' => [
                            'data' =>BankHelper::getBankNames(),
                            'hideSearch' => false,
                            'options'=>[
                                'placeholder' => '选择银行',
                            ]
                        ],
                    ], 
                    
                    'sub_branch'=>['type'=> Form::INPUT_TEXT, 'options'=>[ 'maxlength'=>200]], 
                
                    'opening_address'=>['type'=> Form::INPUT_TEXT, 'options'=>[ 'maxlength'=>255]], 
                    
                    'account_person'=>['type'=> Form::INPUT_TEXT, 'options'=>[ 'maxlength'=>100]], 
                
                    'bankcard_number'=>['type'=> Form::INPUT_TEXT, 'options'=>[ 'maxlength'=>50]], 
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
