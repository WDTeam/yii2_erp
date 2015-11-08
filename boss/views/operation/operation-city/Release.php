<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;

/**
 * @var yii\web\View $this
 * @var dbbase\models\OperationCity $model
 */

$this->title = Yii::t('app', 'release').Yii::t('app', 'City');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'release').Yii::t('app', 'City')];
?>
<div class="operation-city-release">
    <div class="operation-city-form">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    基础信息</h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]); ?>
                <?php echo $form->field($model, 'city_name')->dropDownList($citylist, ['prompt' => '请选择城市', 'id' => 'cityid'])->label('选择发布的城市') ?>

                <?php echo Html::submitButton(Yii::t('app', '下一步'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
                ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>
