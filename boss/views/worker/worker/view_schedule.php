<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use core\models\worker\Worker;
use core\models\worker\WorkerSchedule;
use kartik\daterange\DateRangePicker;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var boss\models\WorkerSearch $searchModel
 */
?>
<style>
    td{cursor:pointer;background: #F3F4F5;}
    tr{margin-bottom: 4px}
    .form-control{line-height:2.0}
    .schedule-date{margin-left: 20px;font-size:14px;width:400px}
    .close{color:red;font-size:14px;margin-top:4px;margin-left: 3px}
    .close:hover{color:red}
    .actives{background: rgba(246, 162, 2, 0.62) none repeat scroll 0% 0%}
    .delete{margin-left: 10px;}
    .switch{margin-left: 10px;}
    .select{background: rgba(177, 209, 228, 0.77) none repeat scroll 0% 0%;}
</style>

<div class="panel panel-info">

    <?php
    if($workerIsInRedis!==false){
        ?>

        <?php
    }else{
        $worker_info = Worker::find()->where(['id'=>$worker_id])->asArray()->one();
        if($worker_info['worker_is_block']!=0){
            $message = '阿姨当前正处于封号中，排班表未激活！';
        }elseif($worker_info['worker_is_blacklist']!=0){
            $message = '阿姨被列入黑名单，排班表未激活！';
        }elseif($worker_info['worker_is_dimission']!=0){
            $message = '阿姨已离职，排班表未激活！';
        }elseif($worker_info['worker_auth_status']<4){
            $message = '阿姨当前还未试工，排班表未激活！';
        }elseif($worker_info['worker_auth_status']==5){
            $message = '阿姨当前试工未通过，排班表未激活！';
        }elseif($worker_info['worker_auth_status']==7){
            $message = '阿姨当前上岗未通过，排班表未激活！';
        }else{
            $message = '排班表未激活，请重新保存';
        }
        ?>
        <div class="callout callout-danger" style="padding: 9px 30px 1px 15px">
            <h4 style="font-size:15px"><?=$message?></h4>
        </div>
        <?php
    }
    ?>
    <div class="box box-solid box-warning">

        <div class="box-body">
            <div style="clear: both"></div>
            <div style="float:left;margin-left:10px;margin-top:4px;font-size: 15px;color:rgb(132, 131, 131)">排班表状态说明：</div>
            <div style="border: 1px solid #DDD;float:left;margin-left:10px;height: 30px;width: 63px;background: rgba(246, 162, 2, 0.62) none repeat scroll 0% 0%;font-size:15px;padding: 6px 12px;"></div>
            <div style="float:left;margin-left:10px;margin-top:4px;font-size: 15px;color:rgb(132, 131, 131)">时间已保存</div>
            <div style="border: 1px solid #DDD;float:left;margin-left:15px;height: 30px;width: 63px;background: rgba(177, 209, 228, 0.77) none repeat scroll 0% 0%;"></div>
            <div style="float:left;margin-left:10px;margin-top:4px;font-size: 15px;color:rgb(132, 131, 131)">时间已选中,未保存</div>
            <div style="border: 1px solid #DDD;float:left;margin-left:15px;height: 30px;width: 63px;background: #F3F4F5"></div>
            <div style="float:left;margin-left:10px;margin-top:4px;font-size: 15px;color:rgb(132, 131, 131)">时间未选中</div>
            <div style="clear: both"></div>
        </div><!-- /.box-body -->
    </div>

<div class="panel-body">
    <div style="width: 300px;float: left">

    <?php
        Yii::t('kvdrp', 'Apply123');
        echo DateRangePicker::widget([
            'name'=>'date_range',
            'useWithAddon'=>false,
            'language'=>'zh-CN',
            'hideInput'=>true,
            'presetDropdown'=>false,
            'initRangeExpr'=>true,
            'pluginOptions'=>[
                'locale'=>['format'=>'date','applyLabel'=>'选择','cancelLabel'=>'取消'],
                'separator'=>' 至 ',
                'opens'=>'right',
            ]
        ]);
    ?>
    </div>
    <button type="button" id='btn-submit' class="btn btn-success" style="float:left;margin-left: 20px;">保存排班表</button>

    <form id='form' method="post" action="./opeation-schedule?id=<?php echo $worker_id?>">
        <input type="hidden" name="schedule_data">
    </form>

</div>


<div id="schedule-list">
    <?php
    $schedule_from_redis = \yii\helpers\ArrayHelper::index($schedule_from_redis,'schedule_id');
    foreach ($schedule as $key=>$val) {
        $weekday_redis = @$schedule_from_redis[$val['id']]['worker_schedule_timeline'];
    ?>
    <div class="schedule_content">
        <div date="<?=date('Y-m-d',$val['worker_schedule_start_date'])?> 至 <?=date('Y-m-d',$val['worker_schedule_end_date'])?>" class="schedule-date">工作日期：<?=date('Y-m-d',$val['worker_schedule_start_date'])?> 至 <?=date('Y-m-d',$val['worker_schedule_end_date'])?>
            <button type="button" class="delete btn btn-xs btn-danger" >删除</button>
        </div>

        <div class="schedule-info panel-body">

            <table  class=" table table-bordered " style="width: 80%;">
            <tbody>
            <?php $weekday = json_decode($val['worker_schedule_timeline'],1)?>
            <?php foreach ($weekday as $w_key=>$w_val) {
                ?>
                <tr style="height: 36px" class="show-schedule-for-redis">
                    <th scope="row" weekday="<?= $w_key?>"><?= WorkerSchedule::getWeekdayShow($w_key)?></th>
                    <td class="<?php if(@in_array('8:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('8:00',$w_val)){echo 'select';}}?>">8:00</td>
                    <td class="<?php if(@in_array('9:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('9:00',$w_val)){echo 'select';}}?>">9:00</td>
                    <td class="<?php if(@in_array('10:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('10:00',$w_val)){echo 'select';}}?>">10:00</td>
                    <td class="<?php if(@in_array('11:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('11:00',$w_val)){echo 'select';}}?>">11:00</td>
                    <td class="<?php if(@in_array('12:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('12:00',$w_val)){echo 'select';}}?>">12:00</td>
                    <td class="<?php if(@in_array('13:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('13:00',$w_val)){echo 'select';}}?>">13:00</td>
                    <td class="<?php if(@in_array('14:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('14:00',$w_val)){echo 'select';}}?>">14:00</td>
                    <td class="<?php if(@in_array('15:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('15:00',$w_val)){echo 'select';}}?>">15:00</td>
                    <td class="<?php if(@in_array('16:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('16:00',$w_val)){echo 'select';}}?>">16:00</td>
                    <td class="<?php if(@in_array('17:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('17:00',$w_val)){echo 'select';}}?>">17:00</td>
                    <td class="<?php if(@in_array('18:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('18:00',$w_val)){echo 'select';}}?>">18:00</td>
                    <td class="<?php if(@in_array('19:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('19:00',$w_val)){echo 'select';}}?>">19:00</td>
                    <td class="<?php if(@in_array('20:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('20:00',$w_val)){echo 'select';}}?>">20:00</td>
                    <td class="<?php if(@in_array('21:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('21:00',$w_val)){echo 'select';}}?>">21:00</td>
                    <td class="<?php if(@in_array('22:00',$weekday_redis[$w_key])){echo 'actives';}else{if(in_array('22:00',$w_val)){echo 'select';}}?>">22:00</td>
                    <th>
                        <input id="blankCheckbox" value="option1" <?php if(count($w_val)==15){echo 'checked';}?> aria-label="..." type="checkbox">
                    </th>
                </tr>
            <?php
            }
            ?>
            </tbody>
            </table>
        </div>
    </div>
    <?php
    }
    ?>
</div>
</div>
<?php $this->registerJsFile('/js/schedule.js',['depends'=>[ 'yii\web\YiiAsset','yii\bootstrap\BootstrapAsset']]); ?>

<!------排班表模板------>
<div id="schedule-template" style="display: none">

<div  class="schedule-info panel-body" >

    <table class="table table-bordered " style="width: 80%;" ">
    <tbody>
    <tr>
        <th scope="row" weekday="1">周一</th>
        <td class="select">8:00</td>
        <td class="select">9:00</td>
        <td class="select">10:00</td>
        <td class="select">11:00</td>
        <td class="select">12:00</td>
        <td class="select">13:00</td>
        <td class="select">14:00</td>
        <td class="select">15:00</td>
        <td class="select">16:00</td>
        <td class="select">17:00</td>
        <td class="select">18:00</td>
        <td class="select">19:00</td>
        <td class="select">20:00</td>
        <td class="select">21:00</td>
        <td class="select">22:00</td>
        <th>
            <input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="...">
        </th>
    </tr>
    <tr>
        <th scope="row" weekday="2">周二</th>
        <td class="select">8:00</td>
        <td class="select">9:00</td>
        <td class="select">10:00</td>
        <td class="select">11:00</td>
        <td class="select">12:00</td>
        <td class="select">13:00</td>
        <td class="select">14:00</td>
        <td class="select">15:00</td>
        <td class="select">16:00</td>
        <td class="select">17:00</td>
        <td class="select">18:00</td>
        <td class="select">19:00</td>
        <td class="select">20:00</td>
        <td class="select">21:00</td>
        <td class="select">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="3">周三</th>
        <td class="select">8:00</td>
        <td class="select">9:00</td>
        <td class="select">10:00</td>
        <td class="select">11:00</td>
        <td class="select">12:00</td>
        <td class="select">13:00</td>
        <td class="select">14:00</td>
        <td class="select">15:00</td>
        <td class="select">16:00</td>
        <td class="select">17:00</td>
        <td class="select">18:00</td>
        <td class="select">19:00</td>
        <td class="select">20:00</td>
        <td class="select">21:00</td>
        <td class="select">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="4">周四</th>
        <td class="select">8:00</td>
        <td class="select">9:00</td>
        <td class="select">10:00</td>
        <td class="select">11:00</td>
        <td class="select">12:00</td>
        <td class="select">13:00</td>
        <td class="select">14:00</td>
        <td class="select">15:00</td>
        <td class="select">16:00</td>
        <td class="select">17:00</td>
        <td class="select">18:00</td>
        <td class="select">19:00</td>
        <td class="select">20:00</td>
        <td class="select">21:00</td>
        <td class="select">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="5">周五</th>
        <td  class="select">8:00</td>
        <td  class="select">9:00</td>
        <td  class="select">10:00</td>
        <td  class="select">11:00</td>
        <td  class="select">12:00</td>
        <td  class="select">13:00</td>
        <td  class="select">14:00</td>
        <td  class="select">15:00</td>
        <td  class="select">16:00</td>
        <td  class="select">17:00</td>
        <td  class="select">18:00</td>
        <td  class="select">19:00</td>
        <td  class="select">20:00</td>
        <td  class="select">21:00</td>
        <td  class="select">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="6">周六</th>
        <td  class="select">8:00</td>
        <td  class="select">9:00</td>
        <td  class="select">10:00</td>
        <td  class="select">11:00</td>
        <td  class="select">12:00</td>
        <td  class="select">13:00</td>
        <td  class="select">14:00</td>
        <td  class="select">15:00</td>
        <td  class="select">16:00</td>
        <td  class="select">17:00</td>
        <td  class="select">18:00</td>
        <td  class="select">19:00</td>
        <td  class="select">20:00</td>
        <td  class="select">21:00</td>
        <td  class="select">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="7">周日</th>
        <td  class="select">8:00</td>
        <td  class="select">9:00</td>
        <td  class="select">10:00</td>
        <td  class="select">11:00</td>
        <td  class="select">12:00</td>
        <td  class="select">13:00</td>
        <td  class="select">14:00</td>
        <td  class="select">15:00</td>
        <td  class="select">16:00</td>
        <td  class="select">17:00</td>
        <td  class="select">18:00</td>
        <td  class="select">19:00</td>
        <td  class="select">20:00</td>
        <td  class="select">21:00</td>
        <td  class="select">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    </tbody>
    </table>
</div>
</div>
