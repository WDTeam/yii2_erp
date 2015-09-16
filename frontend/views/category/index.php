<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\search\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'New skills learning');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="btn-group">
   <?php
        foreach ($dataProvider as $key => $cate) {
         $nopass = $cate['cnum'] - $cate['passnum'];
         
         if($cate['passnum'] <= 0){
            if($cate['nopassnum'] > 0){
                 $btnStyle = 'btn-warning';
                 $btnUrl = ['service', 'id' => $cate['cateid']];
                 $t = '未通过';
            }else{
                $btnStyle = 'btn-success';
                $btnUrl = ['service', 'id' => $cate['cateid']];
                $t = '未考核';
            }
         }else{
             if($nopass > 0){
                 $btnStyle = 'btn-warning';
                 $btnUrl = ['service', 'id' => $cate['cateid']];
                 $t = '未通过';
             }else{
                 $btnStyle = 'btn-default';
                 $btnUrl = 'javascript:void(0);';
                 $t = '已通过';
             }
         }
         echo  Html::a($cate['catename'].'('.$t.')', $btnUrl, ['class'=>'btn btn-lg '.$btnStyle]);
        }
    ?>
</div>






