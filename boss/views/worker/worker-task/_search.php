<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var core\models\worker\WorkerTaskSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<div class="worker-task-search panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 任务搜索</h3>
    </div>
    <div class="panel-body row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]); ?>
        
        <div class="col-md-4">
            <?php echo $form->field($model, 'worker_task_name');?>
        </div>

        <div class="col-md-2" style="margin-top:22px;">
            <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>
    </div>
</div>