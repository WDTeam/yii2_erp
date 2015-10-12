<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
$this->title = Yii::t('app', 'release').Yii::t('app', 'City');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'release').Yii::t('app', 'City'), 'url' => ['release']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择服务'), 'url' => ['categoryshop']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择商圈'), 'url' => ['getcityshopdistrict']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '设置服务'), 'url' => ['settinggoods']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '确认开通页面'), 'url' => ['releaseconfirm']];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Operation Cities'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php    
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
<div class="form-group ">
    <label class="control-label col-md-2">开通城市： <?= $cityname?></label>
    <?php foreach((array)$shopdistrictinfo as $key => $value){
                $shopdistrictname = explode('-', $value);
                $shopdistricnames[] = $shopdistrictname[1];
     }?>
    
    <label class="control-label col-md-3">设置商圈： <?= implode('   ', $shopdistricnames)?></label>
    <br/>
      <br/>
    <ul class="nav nav-tabs nav-stacked">
        <ol>设置服务品类：</ol>
        <?php foreach((array)$categorylist as $key => $value){
                    $categorysname = explode('-', $value);
                    $categorysnames[] = $categorysname[1]; ?>
                    <li><a href='#'><?= $categorysname[1]?></a></li>
        <?php }?>
    </ul>
      <?php foreach((array)$goodsinfo['goodids'] as $key => $value){ ?>
           <div class="form-group ">
                <label class="control-label col-md-2"><?= $goodsinfo['goodnames'][$key] ?></label>
                <div class="col-md-10">
                    市场价格：<input type="text" maxlength="" style="width:50px;" placeholder="市场价格" value="<?= $goodsinfo['operation_goods_market_price'][$key] ?>" name="goodinfo[operation_goods_market_price][]" >元
                    销售价格：<input type="text" maxlength="" style="width:50px;" placeholder="销售价格" value="<?= $goodsinfo['operation_goods_price'][$key] ?>" name="goodinfo[operation_goods_price][]" >元
                    最低消费数量：<input type="text" maxlength="" style="width:70px;" placeholder="最低消费数量" value="<?= $goodsinfo['operation_goods_lowest_consume'][$key] ?>" name="goodinfo[operation_goods_lowest_consume][]" > 
                    开始服务时间：<input type="text" maxlength="" style="width:50px;" placeholder="开始时间" value="<?= $goodsinfo['operation_goods_start_time'][$key] ?>" name="goodinfo[operation_goods_start_time][]" >
                    结束服务时间：<input type="text" maxlength="" style="width:50px;" placeholder="结束时间" value="<?= $goodsinfo['operation_goods_end_time'][$key] ?>" name="goodinfo[operation_goods_end_time][]" >
                </div>
           </div>
      <?php }?>
      <?php foreach((array)$shopdistrictinfo as $key => $value){ ?>
            <?php 
                $shopdistrictInfo = explode('-', $value);
                $shopdistrictid = $shopdistrictInfo[0];
                $shopdistrictname = $shopdistrictInfo[1];
            ?>
            <input type="hidden" name="shopdistrictgoods[shopdistrictid][]" value="<?= $shopdistrictid?>"/>
            <div class="col-md-10 goodssettinginfo"><?= $shopdistrictname?>
                市场价格：<input type="text" class="operation_goods_market_price" maxlength="" style="width:50px;" placeholder="市场价格" value="<?= $settinghopdistrictgoods['operation_goods_market_price'][$key] ?>" name="shopdistrictgoods[operation_goods_market_price][]" >元
                销售价格：<input type="text" class="operation_goods_price" maxlength="" style="width:50px;" placeholder="销售价格" value="<?= $settinghopdistrictgoods['operation_goods_price'][$key] ?>" name="shopdistrictgoods[operation_goods_price][]" >元
                最低消费数量：<input type="text" class="operation_goods_lowest_consume" maxlength="" style="width:70px;" placeholder="最低消费数量" value="<?= $settinghopdistrictgoods['operation_goods_lowest_consume'][$key] ?>" name="shopdistrictgoods[operation_goods_lowest_consume][]" >
                开始服务时间：<input type="text" class="operation_goods_start_time" maxlength="" style="width:50px;" placeholder="开始时间" value="<?= $settinghopdistrictgoods['operation_goods_start_time'][$key] ?>" name="shopdistrictgoods[operation_goods_start_time][]" >
                结束服务时间：<input type="text" class="operation_goods_end_time" maxlength="" style="width:50px;" placeholder="结束时间" value="<?= $settinghopdistrictgoods['operation_goods_end_time'][$key] ?>" name="shopdistrictgoods[operation_goods_end_time][]" >
            </div>
        <?php }?>
      
<?php 
    echo Html::submitButton('确认开通', ['class' => 'btn btn-success']);
    ActiveForm::end();
?>