<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
?>
<?php 
//    echo '<br><input type="checkbox" val="categorylist" id="alllist">全选';
$this->title = Yii::t('app', 'release').Yii::t('app', 'City');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'release').Yii::t('app', 'City'), 'url' => ['release']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择商圈'), 'url' => ['getcityshopdistrict']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择服务品类'), 'url' => ['categoryshop']];
?>
<?php    
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
服务品类
<?php echo Html::checkboxList('categorylist', $categorylistall, $categorylist, ['id' => 'category'])  ?>
<div id="categoryGoodsContent">
</div>

<?php echo Html::submitButton('下一步', ['class' => 'btn btn-success']);
    ActiveForm::end(); ?>