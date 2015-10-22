<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var core\models\search\FinanceCompensate $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="finance-compensate-search">
<div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> <?php echo $this->title?>搜索</h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]); ?>

            <div class='col-md-2'>
                <?= $form->field($model, 'finance_compensate_oa_code') ?>
            </div>

            <div class='col-md-2'>
                <?php echo  $form->field($model, 'finance_compensate_starttime')->widget(DateControl::classname(),[
                    'type' => DateControl::FORMAT_DATE,
                    'ajaxConversion'=>false,
                    'displayFormat' => 'php:Y-m-d',
                    'saveFormat'=>'php:U',
                    'options' => [
                        'pluginOptions' => [
                             'autoclose' => true
                        ]
                    ]
                ]);

                ?>
            </div>  
            <div class='col-md-2'>
                <?php echo  $form->field($model, 'finance_compensate_endtime')->widget(DateControl::classname(),[
                    'type' => DateControl::FORMAT_DATE,
                    'ajaxConversion'=>false,
                    'displayFormat' => 'php:Y-m-d',
                    'saveFormat'=>'php:U',
                    'options' => [
                        'pluginOptions' => [
                             'autoclose' => true
                        ]
                    ]
                ]);
                ?>
            </div> 
            <div class='col-md-2'>
                <?= $form->field($model, 'worker_tel') ?>
            </div>

            <div class="col-md-2" style="margin-top: 22px;">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>

            <?php ActiveForm::end(); ?>
      </div>
    </div>
</div>
