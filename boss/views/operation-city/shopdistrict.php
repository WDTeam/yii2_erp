<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
?>
<?php 
    echo '<br><input type="checkbox" val="shopdistrict" id="alllist">全选';
?>
<?php    
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
<?php echo Html::checkboxList('shopdistrict', $shopdistrict, $shopdistrict)  ?>

<?php echo Html::submitButton('下一步', ['class' => 'btn btn-success']);
    ActiveForm::end(); ?>