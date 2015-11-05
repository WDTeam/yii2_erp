<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;

use core\models\customer\Customer;
use core\models\customer\CustomerBlockLog;
/**
 * @var yii\web\View $this
 * @var core\models\shop\ShopManager $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="customer-add-to-block">

<?php $form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'action'=>['add-to-block', 'id'=>$model->id]
]); ?>

    <?php echo Html::textarea('customer_del_reason', '', [
        'style'=>'width:100%',
        'id'=>'block_reason'
    ])?>
    <input type="hidden" name="id" value="<?= $model->id ?>"/>
    <p id="block_error" style="font-size:10px; color:red;"></p>
    <div class="form-group">
        <div class="col-sm-offset-0 col-sm-12">
            <? //Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);?>
            <?= Html::button('确认', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJsFile('/js/customer-block.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]);
?>