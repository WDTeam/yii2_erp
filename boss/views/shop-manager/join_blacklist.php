<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use core\models\shop\ShopManager;
use boss\components\AreaCascade;

/**
 * @var yii\web\View $this
 * @var core\models\shop\ShopManager $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shop-manager-joinblacklist">

<?php $form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'action'=>['join-blacklist', 'id'=>$model->id]
]); ?>

    <?php echo Html::textarea('cause', '', [
        'style'=>'width:100%'
    ])?>

    <div class="form-group">
        <div class="col-sm-offset-0 col-sm-12">
            <?= Html::submitButton('确认',['class'=>'btn btn-primary btn-lg btn-block']);?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
