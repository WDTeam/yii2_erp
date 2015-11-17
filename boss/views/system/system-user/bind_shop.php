<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\base\Widget;
use kartik\builder\Form;
use kartik\form\ActiveForm;

$this->title = Yii::t('app', '给用户绑定门店');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'System Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-user-bing-shop">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $model->username;?></h3>
        </div>
        <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); ?>
        <div class="panel-body" style="padding:20px 30px;">
            <?php foreach ($shops as $city){?>
            <div class="form-group field-shopmanager-name">
                <h3>
                    <label style="padding-right:20px;"><?php echo $city['city_name'];?></label>
                    <label style="font-size: 14px;">
                        <input type="checkbox" onclick="$('#shopIds_<?php echo $city['city_id'];?> input').prop('checked',this.checked)" />
                        <span>全选</span>
                    </label>
                </h3>
                <div id="shopIds_<?php echo $city['city_id'];?>">
                <?php foreach ($city['items'] as $id=>$item){?>
                    <label title="<?php echo $item;?>" style="display: inline-block; width:210px;white-space:nowrap; overflow:hidden">
                    <?php echo Html::checkbox('SystemUser[shopIds][]', in_array($id, $model->shopIds),[
                        'value'=>$id
                    ]);?>
                    <span><?php echo $item;?></span>
                    </label>
                <?php }?>
                </div>
            </div>
            <hr/>
            <?php }?>
        </div>
        <div class="panel-footer">
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-12">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block']);?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>