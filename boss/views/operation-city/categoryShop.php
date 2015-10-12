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
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '选择服务'), 'url' => ['categoryshop']];
?>
<?php    
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);
?>
服务品类
<?php echo Html::radioList('categorylist[]', $categorylistall, $categorylist)  ?>
<div id="categoryGoodsContent">
</div>

<?php echo Html::submitButton('下一步', ['class' => 'btn btn-success']);
    ActiveForm::end(); ?>