<?php 
use core\models\shop\Shop;

?>
<div class="col-md-2">
    <?php echo Shop::$is_blacklists[(int)$model->is_blacklist];?>
</div>
<div class="col-md-3">
        改变时间：
    <?php echo $model->getLastJoinBlackListTime();?>
</div>
<div class="col-md-7">
        改变原因：
    <?php echo $model->getLastJoinBlackListCause();?>
</div>