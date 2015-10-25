<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
?>
<?php 
    echo '<br><input type="checkbox" val="shopdistrict" id="alllist">全选';
if($addCityGoods == 'success'){  //是否是添加城市商品
    $this->title = $city_name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', $city_name)];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '添加服务'), 'url' => ['categoryshop']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择商圈'), 'url' => ['getcityshopdistrict']];
}elseif($editCityGoods == 'success'){  //是否是编辑城市商品
    $this->title = $city_name;
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', $city_name)];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择商圈'), 'url' => ['getcityshopdistrict']];
}else{  //开通城市
    $this->title = Yii::t('app', 'release').Yii::t('app', 'City');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'release').Yii::t('app', 'City'), 'url' => ['release']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择服务'), 'url' => ['categoryshop']];
}
?>
<?php    
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
<?php echo Html::checkboxList('shopdistrict', $shopdistrictall, $shopdistrict)  ?>

<?php echo Html::submitButton('下一步', ['class' => 'btn btn-success']);
    ActiveForm::end(); ?>