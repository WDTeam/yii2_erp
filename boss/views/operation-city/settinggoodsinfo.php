<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
?>
    选择商圈
<?php
    echo '<br><input type="checkbox" val="shopdistrict" id="alllist">全选';
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
<?php // echo Html::checkboxList('shopdistrict', $shopdistrictinfoall, $shopdistrictinfo)  ?>

    <div class="form-group ">
        <label class="control-label col-md-2"><?= $goodsInfo['operation_goods_name']?></label>
        <label class="control-label col-md-2 settingGoodsinfo">统一设置</label>
        <div class="col-md-10">
            市场价格：<input type="text" maxlength="" style="width:50px;" class="operation_goods_market_price_demo" placeholder="市场价格" value="" name="operation_goods_market_price[]" >元
            销售价格：<input type="text" maxlength="" style="width:50px;" class="operation_goods_price_demo" placeholder="销售价格" value="" name="operation_goods_price[]" >元
            最低消费数量：<input type="text" maxlength="" style="width:70px;" class="operation_goods_lowest_consume_demo" placeholder="最低消费数量" value="" name="operation_goods_lowest_consume[]" > <?= $goodsInfo['operation_spec_strategy_unit']?>
            开始服务时间：<input type="text" maxlength="" style="width:50px;" class="operation_goods_start_time_demo" placeholder="开始时间" value="" name="operation_goods_start_time[]" >
            结束服务时间：<input type="text" maxlength="" style="width:50px;" class="operation_goods_end_time_demo" placeholder="结束时间" value="" name="operation_goods_end_time[]" >
        </div>
        <div class="col-md-offset-2 col-md-10"></div>
        <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
    </div>
    <?php foreach((array)$shopdistrictinfo as $key => $value){ ?>
        <input type="checkbox" <?php if(in_array($key, $shopdistrictinfoall)){ ?>checked="checked"<?php }?> value="<?= $key?>" name="shopdistrict[]">
        <div class="form-group shopdistrictgoods<?= $key?>" > <!--<?php if(empty($goodsshopdistrictinfo[$key])){ ?>style="display: none"<?php }?>-->
            <label class="control-label col-md-2"><?= $value?></label>
            <div class="col-md-10">
                市场价格：<input type="text" maxlength="" style="width:50px;" class="operation_goods_market_price" placeholder="市场价格" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_market_price']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_market_price']?>" name="goodsinfo[operation_goods_market_price][<?= $key?>]" >元
                销售价格：<input type="text" maxlength="" style="width:50px;" class="operation_goods_price" placeholder="销售价格" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_price']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_price']?>" name="goodsinfo[operation_goods_price][<?= $key?>]" >元
                最低消费数量：<input type="text" maxlength="" style="width:70px;" class="operation_goods_lowest_consume" placeholder="最低消费数量" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_lowest_consume_num']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_lowest_consume_num']?>" name="goodsinfo[operation_goods_lowest_consume][<?= $key?>]" > <?= $goodsInfo['operation_spec_strategy_unit']?>
                开始服务时间：<input type="text" maxlength="" style="width:50px;" class="operation_goods_start_time" placeholder="开始时间" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_start_time']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_start_time']?>" name="goodsinfo[operation_goods_start_time][<?= $key?>]" >
                结束服务时间：<input type="text" maxlength="" style="width:50px;" class="operation_goods_end_time" placeholder="结束时间" value="<?= empty($goodsshopdistrictinfo[$key]['operation_shop_district_goods_end_time']) ? '' : $goodsshopdistrictinfo[$key]['operation_shop_district_goods_end_time']?>" name="goodsinfo[operation_goods_end_time][<?= $key?>]" >
            </div>
            <div class="col-md-offset-2 col-md-10"></div>
            <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
        </div>
    <?php }?>

<?php if($cityAddGoods == 'success'){
            $buttonname = '确认添加';
      }else{
            $buttonname = '确认开通';
      }

?>
<?php echo Html::submitButton($buttonname, ['class' => 'btn btn-success']);
    ActiveForm::end(); ?>