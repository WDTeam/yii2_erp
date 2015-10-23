<?php
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="post">
    <h2><?= Html::encode($model->order_code) ?></h2>
    
    <?= HtmlPurifier::process($model->order_address) ?>    
</div>