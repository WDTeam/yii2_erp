<?php
use yii\helpers\Html;
if(isset($version)){
    $str = $platform->id.'_'.$version->id;
}else{
    $str = $platform->id;
}
?>
<div class="row step4_<?php echo $str?>">
    <?php foreach((array)$infos as $key => $value){?>
    <div class="col-md-4">
    <?php
        echo Html::checkbox('OperationAdvertRelease[operation_advert_content_id][]', false, ['value' => $value['id']]);
        echo $value['operation_advert_content_name'].'('.$value['platform_name'].':'.$value['platform_version_name'].')';
    ?></div>
    <?php }?>
</div>