<style>
*{ margin:0; padding:0;}
    body { font:12px/19px Arial, Helvetica, sans-serif; color:#666;}
    .tab_menu,.tab_box,.tab_text ,.btn_tab_box{float: left;}
    .hid{display:none}
    label {width: 150px;height:30px; line-height:30px;background-color: #f6a202; margin: 5px 30px; border-radius: 5px;}
    .boxx {padding:4px;}
</style>
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
$form = ActiveForm::begin([
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype' => 'multipart/form-data'],
    'action' => ['settinggoodsinfo'],
]);
?>
<!--
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"> 服务品类</h3>
        </div>
    <div class="panel-body ">
        <input type="hidden" class="city_id" name="city_id" value="<?= $city_id?>" />
        <div class="row">
            <div class="col-md-2">先选择品类</div>
            <div class="col-md-offset-2 col-md-2">后选择商品</div>
        </div>
        <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
            <?php //echo Html::radioList('categorylist[]', [], $categorylist, ['class'=>'MyRadioStyle col-md-2'])  ?>
            <div class="col-md-2"></div>
            <div id="categoryGoodsContent" class="MyRadioStyle col-md-6">
            </div>
        </div>
    </div>
        <div class="panel-footer">
            <div class="form-group">
                <div class="col-sm-12">
                <?php //echo Html::submitButton('下一步', ['class' => 'btn btn-success btn-lg btn-block']);?>
                </div>
            </div>
        </div>
-->

    <input type="hidden" class="city_id" name="city_id" value="<?= $city_id?>" />
    <input type="hidden" class="city_name" name="city_name" value="<?= $city_name?>" />

	<div class="tab_menu">
        <?php
            //实现方式：把所有的数据都先查出来，对应隐藏;目前没有找到更好的实现方法
            //服务类型数据
            foreach ($categorylist as $key => $value) {
                echo '<label for="cate_'. $key .'"><input id="cate_'. $key .'" type="radio" name="'. $key .'" value="'.$key.'"/>'.$value.'</label><br>';
            }    						  
        ?>
	</div>

	<div class="tab_box">
        <?php
            //服务类型下对应的服务项目数据
            foreach ($goods as $key => $value) {

                echo '<div class="hid ipo">';
                foreach ($value as $k => $v) {

                    //隐藏的服务项目名称信息
                    echo '<input type="hidden" name="'. $key . '['. $v['id'] .']' .'[operation_goods_name]" value="'. $v['operation_goods_name'] .'" />';

                    echo '<label for="goods_'. $key . $k .'"><input id="goods_'. $key . $k .'" type="checkbox" name="'. $key . '['. $v['id'] .']' .'[operation_goods_id]" value="'.$v['id'].'"/>'.$v['operation_goods_name'].'</label><br>';
                }
                echo '</div>';
            }    						  
        ?>
	</div>

	<div class="tab_text">
        <?php
            //服务项目下对应的价格数据
            foreach ($goods as $key => $value) {

                foreach ($value as $k => $v) {
                    echo '<div class="hid">';

                        //隐藏的规格信息
                        echo '<input type="hidden" name="'. $key .'['. $v['id'] .'][operation_spec_strategy_unit]" value="'. $v['operation_spec_strategy_unit'] .'" />';

                        echo '<div class="boxx">服务项目：<span>'. $v['operation_category_name'] .'</span></div>';
                        echo '<div class="boxx">销售价格：<input type="text" name="'. $key .'['. $v['id'] .'][operation_goods_price]">元/' . $v['operation_spec_strategy_unit'] . '</div>';
    
                        echo '<div class="boxx">市场价格：<input type="text" name="'. $key .'['. $v['id'] .'][operation_goods_market_price]">元/' . $v['operation_spec_strategy_unit'] . '</div>';
                        echo '<div class="boxx">最低消费：<input type="text" name="'. $key .'['. $v['id'] .'][operation_shop_district_goods_lowest_consume_num]">元/' . $v['operation_spec_strategy_unit'] . '</div>';
                    echo '</div>';
                }
            }    						  
        ?>
	</div>

	<div class="btn_tab_box">
        <?php
            //当前城市下对应的商圈数据
            foreach ($goods as $key => $value) {

                foreach ($value as $k => $v) {
                    echo '<div class="hid btn_ipo">';
                    foreach ((array)$shopdistrictinfo as $id => $name) {
                        echo '<label for="district_'. $key . $k . $id .'">' .$name. '<input id="district_'. $key . $k . $id .'" type="checkbox" name="'. $key .'['. $v['id'] .'][district][]" value="'. $id .'"></label><br>';
                    }
                    echo '</div>';
                }
            }    						  
        ?>
    </div>

    <div>
        <input type="submit" />
    </div>

<?php ActiveForm::end(); ?>
