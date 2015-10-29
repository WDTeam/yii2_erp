<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use dbbase\models\Customer;
use boss\components\AreaCascade;

/**
 * @var yii\web\View $this
 * @var boss\models\ShopManager $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-add-to-block">

<?php $form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'action'=>['add-to-block', 'id'=>$model->id]
]); ?>

    <?php echo Html::textarea('customer_del_reason', '', [
        'style'=>'width:100%'
    ])?>

    <div class="form-group">
        <div class="col-sm-offset-0 col-sm-12">
            <?= Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
