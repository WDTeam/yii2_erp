<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;

$this->title = Yii::t('app', 'Set Service');
?>
<?php

if(!empty($cityAddGoods)){
    $this->params['breadcrumbs'][] = ['label' => $city_name];
}else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '开通') . $city_name, 'url' => ['categoryshop']];
}
if($cityAddGoods == 'editGoods') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '编辑服务')];

}else{
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '添加服务'), 'url' => ['addgoods', 'city_id' => $city_id, 'cityAddGoods' => $cityAddGoods]];
}
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '设置服务')];
?>
<?php
$form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
    <div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">选择商圈</h3>
    </div>
    <div class="panel-body">
        <?php echo '<br><input type="checkbox" val="shopdistrict" id="alllist">全选<div class="div-inline"></div>'; ?>
        <?php // echo Html::checkboxList('shopdistrict', $shopdistrictinfoall, $shopdistrictinfo)  ?>
        <div class="form-group ">
            <label class="control-label col-md-2"><?= $goodsInfo['operation_goods_name']?>&nbsp;&nbsp;&nbsp;</label><label class="settingGoodsinfo">统一设置</label>
            <!-- <label class="control-label col-md-2 settingGoodsinfo"></label>-->
            <div class="col-md-10 not-bold">
                <label>市场价格：</label>
                <input type="text" maxlength="" style="width:50px;" class="operation_goods_market_price_demo" value="" name="operation_goods_market_price[]" >元/<?= $goodsInfo['operation_spec_strategy_unit']?>
                <div class="div-inline"></div>

                <label>销售价格：</label>
                <input type="text" maxlength="" style="width:50px;" class="operation_goods_price_demo" value="" name="operation_goods_price[]" >元/<?= $goodsInfo['operation_spec_strategy_unit']?>
                <div class="div-inline"></div>

                <label>最低消费数量：</label>
                <input type="text" maxlength="" style="width:70px;" class="operation_goods_lowest_consume_demo" value="" name="operation_goods_lowest_consume[]" >
                <?= $goodsInfo['operation_spec_strategy_unit']?>
                <div class="div-inline"></div>

                <label>开始服务时间：</label>
                <input type="text" maxlength="" style="width:120px;" class="operation_goods_start_time_demo" value="" name="operation_goods_start_time[]">
                <div class="div-inline"></div>

                <label>结束服务时间：</label>
                <input type="text" maxlength="" style="width:120px;" class="operation_goods_end_time_demo" value="" name="operation_goods_end_time[]" >
            </div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
        </div>

        <?php foreach((array)$shopdistrictinfo as $key => $value){ ?>
            <input type="checkbox" <?php if(in_array($key, $shopdistrictinfoall)){ ?>checked="checked"<?php }?> value="<?= $key?>" name="shopdistrict[]">
            <div class="form-group shopdistrictgoods<?= $key?>" > <!--<?php if(empty($goodsshopdistrictinfo[$key])){ ?>style="display: none"<?php }?>-->
                <label class="control-label col-md-2"><?= $value?></label>
                <div class="col-md-10 not-bold">
                    <label>市场价格：</label>
                    <input type="text" maxlength="" style="width:50px;" class="operation_goods_market_price" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_market_price']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_market_price']?>" name="goodsinfo[operation_goods_market_price][<?= $key?>]" >
                    元/<?= $goodsInfo['operation_spec_strategy_unit']?>
                    <div class="div-inline"></div>

                    <label>销售价格：</label>
                    <input type="text" maxlength="" style="width:50px;" class="operation_goods_price" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_price']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_price']?>" name="goodsinfo[operation_goods_price][<?= $key?>]" >
                    元/<?= $goodsInfo['operation_spec_strategy_unit']?>
                    <div class="div-inline"></div>

                    <label>最低消费数量：</label>
                    <input type="text" maxlength="" style="width:70px;" class="operation_goods_lowest_consume" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_lowest_consume_num']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_lowest_consume_num']?>" name="goodsinfo[operation_goods_lowest_consume][<?= $key?>]" > <?= $goodsInfo['operation_spec_strategy_unit']?>
                    <div class="div-inline"></div>

                    <label>开始服务时间：</label>
                    <input type="text" maxlength="" style="width:120px;" class="operation_goods_start_time" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_start_time']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_start_time']?>" name="goodsinfo[operation_goods_start_time][<?= $key?>]" >
                    <div class="div-inline"></div>

                    <label>结束服务时间：</label>
                    <input type="text" maxlength="" style="width:120px;" class="operation_goods_end_time" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_end_time']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_end_time']?>" name="goodsinfo[operation_goods_end_time][<?= $key?>]" >
                </div>
                <div class="col-md-offset-2 col-md-10"></div>
                <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
            </div>
        <?php }?>
    </div>
    <div class="panel-footer">
        <div class="form-group">
            <div class="col-sm-offset-0 col-sm-12">
                <?php if($cityAddGoods == 'success'){
                    $buttonname = '确认添加';
                }elseif($cityAddGoods == 'editGoods'){
                    $buttonname = '确认编辑';
                }
                else{
                    $buttonname = '确认开通';
                }

                ?>
                <?php echo Html::submitButton($buttonname, ['class' => 'btn btn-success btn-lg btn-block']); ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
