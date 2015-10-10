<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model boss\models\Operation\OperationAdvertRelease */

$this->title = Yii::t('app', 'Release Advert');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Advert Release'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
<label class="control-label" for="operationadvertrelease-city_id">第二步：选择要的目标版本</label>

<div class="form-group">
    <?=Html::checkboxList('platform_id[]', null, $platforms, ['class' => 'step2']);?>
</div>

<div class="form-group">
    <?=Html::submitButton('下一步', ['class' => 'btn btn-success form-control']) ?>
</div>
<?php ActiveForm::end(); ?>