<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = '岗前学习';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if($is_mobile){?>
<div class="label label-warning">你正在使用手机访问，如果非WIFI连接，可能消耗你较大流量。</div>
<?php }?>
<div class="study-pre-service">
    <h3><?=$courseware->name;?></h3>
    <div id="videoPlayer"></div>
    <div style="text-align:center; padding:10px">
        <?php echo Html::a('考试(认同)',['study/pre-service-answer',
            'courseware_id'=>$courseware->id,
            'order_number'=>$courseware->order_number
        ],['class'=>'btn btn-primary']);?>
        <?php echo Html::a('不认同',['study/abandon', 'name'=>$courseware->name],['class'=>'btn btn-primary']);?>
    </div>
</div>
<script type="text/javascript" charset="utf-8" src="http://cdn.aodianyun.com/lss/ckplayer/player.js"></script>
<?php $this->registerJs(<<<JSCONTENT
    var w = $('.study-pre-service').width();//视频宽度
    var h = (1080/1920)*w;//视频高度
    var url = "{$courseware->url}";//视频地址
    var image = "{$courseware->image}";//封面图片
    var flashvars={f:'http://cdn.aodianyun.com/lss/ckplayer/m3u8.swf',a:url,c:0,s:4,i:image,lv:0};
    var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always',wmode:'transparent'};
    var video=[url];
    CKobject.embed('http://cdn.aodianyun.com/lss/ckplayer/ckplayer.swf','videoPlayer','ckplayerFlashBox',w,h,false,flashvars,video,params);
JSCONTENT
);?>