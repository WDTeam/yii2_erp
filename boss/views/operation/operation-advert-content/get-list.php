<?php
use yii\helpers\Html;
?>
<div class="container-fluid selectBox">
    <div class="row">
        <?php if(!empty($infos)){?>
            <?php foreach((array)$infos as $key => $value){?>
                <div class="col-md-4">
                    <a class="selectContent" href="javascript:void(0)" content_id="<?php echo $value['id'] ?>">
                        <span><?php echo $value['operation_advert_position_name'].'('.$value['operation_city_name'].':'.$value['operation_platform_name'].$value['operation_platform_version_name'].')'?></span>
                    </a>
                </div>
            <?php }?>
        <?php }else{ ?>
        <div class="col-md-12">
            没有可选数据
        </div>
        <?php }?>
    </div>
</div>
<?php if(!empty($infos)){?>
<div class="container-fluid">
    <div class="btn-group btnBox">
        <?= Html::button('确认', ['class' => 'btn btn-primary selectQuery', 'title' => '确认'])?>
        <?= Html::button('取消', ['class' => 'btn btn-default cancel', 'title' => '取消'])?>
    </div>
</div>
<?php }?>