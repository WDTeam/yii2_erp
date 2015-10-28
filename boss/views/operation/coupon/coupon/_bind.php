<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;

?>

<div class="coupon-bind">

<?php $form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'action'=>['bind', 'id'=>$model->id]
]); ?>

    <?php echo Html::textInput('cause', '', [
        'style'=>'width:100%; padding:10px; font-size:24px;'
    ])?>

    <div class="form-group">
        <div class="col-sm-offset-0 col-sm-12">
            <?= Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
