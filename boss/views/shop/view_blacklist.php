<?php 
use core\models\shop\Shop;

$creatd_time = $model->getLastJoinBlackList()->created_at;
?>
<div class="col-md-2">
    <?php echo Shop::$is_blacklists[(int)$model->is_blacklist];?>
</div>
<div class="col-md-3">
        改变时间：
    <?php echo date('Y-m-d H:i:s', $creatd_time);?>
</div>
<div class="col-md-7">
        改变原因：
    <?php echo $model->getLastJoinBlackList()->cause;?>
</div>