<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use boss\components\AreaCascade;
?>
<?php 
//    echo '<br><input type="checkbox" val="categorylist" id="alllist">全选';
?>
<div id="goods_<?= $categoryid?>">
    <?php echo Html::checkboxList('categorygoods', $categorygoods, $categorygoods)  ?>
</div>