<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
?>
<?php
$this->title = Yii::t('app', 'Add Service');
//    echo '<br><input type="checkbox" val="categorylist" id="alllist">全选';
if($cityAddGoods == 'success'){
    $this->params['breadcrumbs'][] = ['label' => $city_name];
}else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '开通') . $city_name, 'url' => ['categoryshop']];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '添加服务')];
?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"> 服务品类</h3>
        </div>
    <div class="panel-body">
        <?php
            $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
        ?>
        <input type="hidden" class="city_id" name="city_id" value="<?= $city_id?>" />

        <?php echo Html::radioList('categorylist[]', [], $categorylist)  ?>
        <div id="categoryGoodsContent">
        </div>
        <?php echo Html::submitButton('下一步', ['class' => 'btn btn-success']);
            ActiveForm::end(); ?>