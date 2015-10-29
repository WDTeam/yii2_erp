<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
use dosamigos\datetimepicker\DateTimePicker;

if($addCityGoods == 'success'){  //是否是添加城市商品
    $this->title = $city_name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', $city_name)];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '添加服务'), 'url' => ['categoryshop']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择商圈'), 'url' => ['getcityshopdistrict']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '设置服务'), 'url' => ['settinggoods']];
}elseif($editCityGoods == 'success'){  //是否是编辑城市商品
    $this->title = $city_name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', $city_name)];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择商圈'), 'url' => ['getcityshopdistrict']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '设置服务'), 'url' => ['settinggoods']];
}else{  //开通城市
    $this->title = Yii::t('app', 'release').Yii::t('app', 'City');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'release').Yii::t('app', 'City'), 'url' => ['release']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择服务'), 'url' => ['categoryshop']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择商圈'), 'url' => ['getcityshopdistrict']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '设置服务'), 'url' => ['settinggoods']];
}
?>

<div class="panel-heading">
    <h3 class="panel-title"></h3>
    <input type="hidden" name="OperationGoods[operation_spec_name]" value="">
</div>
<?php    
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
<?php foreach((array)$goodslist as $key => $value){ ?>
    <div class="form-group ">
        <label class="control-label col-md-2"><?= $value['operation_goods_name']?></label>
        <input type="hidden" name="goodinfo[goodids][]" value="<?= $value['id']?>"/>
        <input type="hidden" name="goodinfo[goodnames][]" value="<?= $value['operation_goods_name']?>"/>
        
        <div class="col-md-10 goodssettinginfo goodssettinginfodemo">统一价格  
            市场价格：<input type="text" class="operation_goods_market_price_demo" maxlength="" style="width:50px;" placeholder="市场价格" value="<?= $settinggoodsinfo['operation_goods_market_price'][$key] ?>" name="goodinfo[operation_goods_market_price][]" >元
            销售价格：<input type="text" class="operation_goods_price_demo" maxlength="" style="width:50px;" placeholder="销售价格" value="<?= $settinggoodsinfo['operation_goods_price'][$key] ?>" name="goodinfo[operation_goods_price][]" >元
            最低消费数量：<input type="text" class="operation_goods_lowest_consume_demo" maxlength="" style="width:70px;" placeholder="最低消费数量" value="<?= $settinggoodsinfo['operation_goods_lowest_consume'][$key] ?>" name="goodinfo[operation_goods_lowest_consume][]" > <?= $value['operation_spec_strategy_unit']?>
            开始服务时间：<input type="text" class="operation_goods_start_time_demo" maxlength="" style="width:50px;" placeholder="开始时间" value="<?= $settinggoodsinfo['operation_goods_start_time'][$key] ?>" name="goodinfo[operation_goods_start_time][]" >
            结束服务时间：<input type="text" class="operation_goods_end_time_demo" maxlength="" style="width:50px;" placeholder="结束时间" value="<?= $settinggoodsinfo['operation_goods_end_time'][$key] ?>" name="goodinfo[operation_goods_end_time][]" >
            <div id="goodssetting">统一设置</div>
        </div>
        <?php foreach((array)$shopdistrictall as $key => $value){ ?>
            <?php 
                $shopdistrictInfo = explode('-', $value);
                $shopdistrictid = $shopdistrictInfo[0];
                $shopdistrictname = $shopdistrictInfo[1];
            ?>
            <input type="hidden" name="shopdistrictgoods[shopdistrictid][]" value="<?= $shopdistrictid?>"/>
            <div class="col-md-10 goodssettinginfo"><?= $shopdistrictname?>
                市场价格：<input type="text" class="operation_goods_market_price" maxlength="" style="width:50px;" placeholder="市场价格" value="<?= empty($settinghopdistrictgoods['operation_goods_market_price'][$shopdistrictid]) ? '': $settinghopdistrictgoods['operation_goods_market_price'][$shopdistrictid]; ?>" name="shopdistrictgoods[operation_goods_market_price][<?= $shopdistrictid?>]" >元
                销售价格：<input type="text" class="operation_goods_price" maxlength="" style="width:50px;" placeholder="销售价格" value="<?= empty($settinghopdistrictgoods['operation_goods_price'][$shopdistrictid]) ? '' : $settinghopdistrictgoods['operation_goods_price'][$shopdistrictid]; ?>" name="shopdistrictgoods[operation_goods_price][<?= $shopdistrictid?>]" >元
                最低消费数量：<input type="text" class="operation_goods_lowest_consume" maxlength="" style="width:70px;" placeholder="最低消费数量" value="<?= empty($settinghopdistrictgoods['operation_goods_lowest_consume'][$shopdistrictid]) ? '' : $settinghopdistrictgoods['operation_goods_lowest_consume'][$shopdistrictid]; ?>" name="shopdistrictgoods[operation_goods_lowest_consume][<?= $shopdistrictid?>]" >
                开始服务时间：<input type="text" class="operation_goods_start_time" maxlength="" style="width:50px;" placeholder="开始时间" value="<?= empty($settinghopdistrictgoods['operation_goods_start_time'][$shopdistrictid]) ? '' : $settinghopdistrictgoods['operation_goods_start_time'][$shopdistrictid]; ?>" name="shopdistrictgoods[operation_goods_start_time][<?= $shopdistrictid?>]" >
                结束服务时间：<input type="text" class="operation_goods_end_time" maxlength="" style="width:50px;" placeholder="结束时间" value="<?= empty($settinghopdistrictgoods['operation_goods_end_time'][$shopdistrictid]) ? '' : $settinghopdistrictgoods['operation_goods_end_time'][$shopdistrictid]; ?>" name="shopdistrictgoods[operation_goods_end_time][<?= $shopdistrictid?>]" >
            </div>
        <?php }?>
        <div>
            <?php 
                
            ?>
            <?php
//                echo DateTimePicker::widget([
//                        'name' => 'stat_time[]',
//        //                'type' => '',
//                        'language' => 'es',
//                        'template' => '{input}',
//                        'pickButtonIcon' => 'glyphicon glyphicon-time',
//                        'inline' => true,
//                        'size' => 'ms',
//                        'options' => [
//                                'readonly' => true
//                        ],
//                        'clientOptions' => [
//                            'startView' => 1,
//                            'minView' => 0,
//                            'maxView' => 1,
//                            'autoclose' => true,
//                            'linkFormat' => 'HH:ii ', // if inline = true
//                            'todayBtn' => true,
//                            'todayHighlight' => true,
//                        ]
//                ]);

                ?>
            <?php
//                echo DateTimePicker::widget([
//                        'name' => 'stat_time[]',
//        //                'type' => '',
//                        'language' => 'es',
//                        'template' => '{input}',
//                        'pickButtonIcon' => 'glyphicon glyphicon-time',
//                        'inline' => true,
//                        'size' => 'ms',
//                        'options' => [
//                                'readonly' => true
//                        ],
//                        'clientOptions' => [
//                            'startView' => 1,
//                            'minView' => 0,
//                            'maxView' => 1,
//                            'autoclose' => true,
//                            'linkFormat' => 'HH:ii ', // if inline = true
//                            'todayBtn' => true,
//                            'todayHighlight' => true,
//                        ]
//                ]);
                ?>
        </div>
        <div class="col-md-offset-2 col-md-10"></div>
        <div class="col-md-offset-2 col-md-10"><div class="help-block"></div></div>
    </div>
<?php }?>
<?php echo Html::submitButton('下一步', ['class' => 'btn btn-success']);
    ActiveForm::end(); ?>