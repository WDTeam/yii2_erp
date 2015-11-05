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
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '开通') . $city_name, 'url' => ['release']];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '添加服务')];
?>
<?php
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"> 服务类型</h3>
        </div>
    <div class="panel-body ">
        <input type="hidden" class="city_id" name="city_id" value="<?= $city_id?>" />
        <div class="row">
            <div class="col-md-2">先选择服务类型</div>
            <div class="col-md-offset-1 col-md-2">后选择服务项目</div>
            <div class="col-md-offset-2 col-md-1">定价</div>
            <div class="col-md-offset-1 col-md-2">适用商圈</div>
        </div>
        <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
            <?php echo Html::radioList('categorylist[]', [], $categorylist, ['class'=>'MyRadioStyle col-md-2'])  ?>
            <div id="categoryGoodsContent" class="MyRadioStyle col-md-3">
            </div>
            <div id="categoryGoodsPrice" class="MyRadioStyle col-md-3">
            </div>
        </div>
    </div>
        <div class="panel-footer">
            <div class="form-group">
                <div class="col-sm-12">
                <?php echo Html::submitButton('下一步', ['class' => 'btn btn-success btn-lg btn-block']);?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
