<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of _query_links
 *
 * @author weibeinan
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;


?>
<?php 
 $form = ActiveForm::begin([
     'action'=>['index'],
     'method'=>'get',
 ]);
?>
<?php echo Html::a('导出报表',['export'],['class'=>'btn btn-success']) ?>
<?php ActiveForm::end(); ?>
