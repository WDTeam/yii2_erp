<?php

use yii\helpers\BaseMarkdown;

$this->title = Yii::t('app', '发布记录');
?>
<div class="site-index">

    <div class="panel panel-info">
        <?php 
            $dir =dirname(__FILE__);
            $handle = fopen($dir.'/releasenotes.md', 'r');
            while(!feof($handle)){
                echo BaseMarkdown::process(fgets($handle,1024));
            }
        ?>
    </div>

</div>
