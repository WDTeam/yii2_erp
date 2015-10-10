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
    <div class="col-md-6" style="padding:0">
        <?php echo \kartik\widgets\Select2::widget([
            'model'=>$model,
            'attribute'=>$shop_manager_id,
//            'initValueText'=>'1',
            'data' =>$shop_managers,
            'hideSearch' => false,
            'options'=>[
                'placeholder' => '选择家政',
            ],
            'pluginEvents' => [
                'change' => 'function (model) {
                    window.'.$widget_id.'shop_manager_id = this.value;
                }'
            ],
        ]);?>
    </div>
    <div class="col-md-6" style="padding:0">
        <?php echo \kartik\widgets\Select2::widget([
            'model'=>$model,
            'attribute'=>$shop_id,
//            'initValueText'=>'1',
            'options'=>[
                'placeholder' => '选择门店',
            ],
            'data'=>$shops,
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'ajax' => [
                    'url' => \yii\helpers\Url::to(['shop/search-by-name']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) {
                        return {name:params.term, shop_manager_id: window.'.$widget_id.'shop_manager_id};
                    }')
                ],
                //                     'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(model) { return model.name; }'),
                'templateSelection' => new JsExpression('function (model) { return model.name; }'),
            ],
        ]);?>
    </div>
</div>

