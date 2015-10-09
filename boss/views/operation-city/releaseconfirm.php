<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
$this->title = Yii::t('app', '确认开通页面');
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Cities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php    
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
<div class="form-group ">
    <label class="control-label col-md-2"><?= $cityname?></label>
</div>