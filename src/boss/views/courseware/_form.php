<?php

// use yii\helpers\Html;
use \kartik\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use backend\models\Courseware;
use backend\models\Category;

/**
 * @var yii\web\View $this
 * @var backend\models\Courseware $model
 * @var yii\widgets\ActiveForm $form
 */
$videos = Courseware::getVideoList();
$classifys = Category::getAllClassifys();
?>
<?php $this->registerJs(<<<JSCODE
window.setData = function (data, ths){
//     console.log(data);
    $('#videoList li').removeClass('selected');
    $(ths).addClass('selected');
    $('#coursewareFormBtn').removeClass('hide');
    $('#courseware-url').val(data.m3u8_480);
    $('#courseware-image').val(data.thumbnail);
    $('#courseware-name').val(data.title);
}
JSCODE
);?>
<div class="courseware-form">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);?>
    <h2>视频选择: </h2>
    
    <ul id="videoList">
        <?php foreach ($videos as $video){?>
        <li class="video-list-item" onclick='setData(<?php echo json_encode($video);?>, this)'>
            <?php echo Html::img($video['thumbnail'],['width'=>200]);?>
            <p>标题：<?php echo $video['title']?></p>
            <p>分类：<?php echo $video['caseName'];?></p>
        </li>
        <?php }?>
    </ul>
    <?php echo Html::activeHiddenInput($model, 'url')?>
    <?php echo Html::activeHiddenInput($model, 'image')?>
    <?php echo Html::activeHiddenInput($model, 'name')?>
    <a class="btn btn-primary" target="_blank" href="http://www.aodianyun.com/console/index.php?r=dvr/upDvrList">上传视频</a>
    <?php echo Html::submitButton('确认',[
        'class'=>'btn btn-primary pull-right hide',
        'id'=>'coursewareFormBtn'
        
    ]);?>
    <?php ActiveForm::end();?>
</div>
