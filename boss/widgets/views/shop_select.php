<?php
/**
 * Created by PhpStorm.
 * User: colee
 * Date: 2015/10/10
 * Time: 11:34
 */
use yii\web\JsExpression;
?>
<div class="row">
    <div class="col-md-6">
        <?php echo \kartik\widgets\Select2::widget([
            'name'=>'shop_manager_id',
            'initValueText'=>'1',
            'data' =>$shop_managers,
            'hideSearch' => false,
            'options'=>[
                'placeholder' => '选择家政',
            ],
            'pluginEvents' => [
                'change' => 'function (model) {
                    var shop_manamger_id = this.value;
                    $("#'.$widget_id.'ShopId").select2({
                        data: [{id:1, text:1111},{id:2, text:2222222}]
                    });
                }'
            ],
        ]);?>
    </div>
    <div class="col-md-6">
        <?php echo \kartik\widgets\Select2::widget([
            'id'=>$widget_id.'ShopId',
            'name'=>'shop_id',
            'options'=>[
                'placeholder' => '选择门店',
            ],
        ]);?>
    </div>
</div>

