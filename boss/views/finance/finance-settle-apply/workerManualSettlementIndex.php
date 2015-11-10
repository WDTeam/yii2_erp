<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
$this->title = Yii::t('app', '阿姨结算');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="worker-index">
    <div id = "manualSettle" class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="glyphicon glyphicon-search"></i> 阿姨搜索</h3>
        </div>

        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                 'type' => ActiveForm::TYPE_HORIZONTAL,
                 //'id' => 'login-form-inline',
                 'method' => 'get',
                 ]);


            ?>
            <div class='col-md-6'>
                <?= $form->field($model, 'worker_tel')->textInput(['id'=>'worker_tel']) ?>
            </div>
            <div class='col-md-2' style='margin-top: 22px;'>
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
            </div>
            <div class='col-md-4'>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    <div id = "manualSettleInfo" style="display:none">
        
        <?php

            echo $this->render('workerSettleCommonView', ['model'=>$model,'orderDataProvider'=>$orderDataProvider,'cashOrderDataProvider'=>$cashOrderDataProvider,'taskDataProvider'=>$taskDataProvider,'compensateDataProvider'=>$compensateDataProvider]);
            ?>
        
    </div>
</div>
         <?php 
         
            $js=<<<JS
                    $(document).ready(
                        function(){
                            var worker_tel = $('#worker_tel').val();
                            if(worker_tel == ''){
                                $('#manualSettleInfo').html('<h4  class="col-sm-12">请输入查询条件进行人工结算</h4>');
                                $('#manualSettleInfo').show();
                            }else{
                                $('#manualSettleInfo').show();
                            }
                        }
                    );
JS;
        $this->registerJs(
                $js
        );
         ?>

