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
    td{cursor:pointer}
    tr{margin-bottom: 4px}
    .form-control{line-height:2.0}
    .schedule-date{margin-left: 20px;font-size:14px;width:400px}
    .close{color:red;font-size:14px;margin-top:4px;margin-left: 3px}
    .close:hover{color:red}
    .disabled{background: #F3F4F5}
    .delete{margin-left: 10px;}
    .switch{margin-left: 10px;}
</style>
<div class="panel panel-info">
    <?php
    if($workerIsInRedis!==false){
        ?>
        <div class="callout callout-info" style="padding: 9px 30px 1px 15px">
            <h4  style="font-size:15px">阿姨排班表可用。</h4>
        </div>
        <?php
    }else{
        $worker_info = Worker::find()->where(['id'=>$worker_id])->asArray()->one();
        if($worker_info['worker_is_block']!=0){
            $message = '阿姨当前正处于封号中，排班表不可用！';
        }elseif($worker_info['worker_is_blacklist']!=0){
            $message = '阿姨被列入黑名单，排班表不可用！';
        }elseif($worker_info['worker_is_dimission']!=0){
            $message = '阿姨已离职，排班表不可用！';
        }elseif($worker_info['worker_auth_status']<4){
            $message = '阿姨当前还未试工，排班表不可用！';
        }elseif($worker_info['worker_auth_status']==5){
            $message = '阿姨当前试工未通过，排班表不可用！';
        }elseif($worker_info['worker_auth_status']==7){
            $message = '阿姨当前上岗未通过，排班表不可用！';
        }else{
            $message = '未知，请重新保存';
        }
        ?>
        <div class="callout callout-danger" style="padding: 9px 30px 1px 15px">
            <h4 style="font-size:15px"><?=$message?></h4>
        </div>
        <?php
    }
    ?>
<div class="panel-body">
    <div style="width: 300px;float: left">

    <?php
        echo DateRangePicker::widget([
            'name'=>'date_range',
            'useWithAddon'=>false,
            'language'=>'zh-CN',
            'hideInput'=>true,
            'presetDropdown'=>false,
            'initRangeExpr'=>true,
            'pluginOptions'=>[
                'locale'=>['format'=>'date'],
                'separator'=>' 至 ',
                'opens'=>'right',
            ]
        ]);
    ?>
    </div>
    <button type="button" id='btn-add' class="btn btn-success" style="margin-left: 20px">添加日期</button>
    <button type="button" id='btn-submit' class="btn btn-success" style="margin-left: 20px;">保存排班表</button>
    <form id='form' method="post" action="./opeation-schedule?id=<?php echo $worker_id?>">
        <input type="hidden" name="schedule_data">
    </form>

</div>


<div id="schedule-list">
    <?php

    foreach ($schedule as $val) {

    ?>
    <div class="schedule_content">
        <div date="<?=date('Y-m-d',$val['worker_schedule_start_date'])?> 至 <?=date('Y-m-d',$val['worker_schedule_end_date'])?>" class="schedule-date">工作日期：<?=date('Y-m-d',$val['worker_schedule_start_date'])?> 至 <?=date('Y-m-d',$val['worker_schedule_end_date'])?>
            <button type="button" class="delete btn btn-xs btn-danger" >删除</button>
            <button show_type='schedule-for-mysql' type="button" class="switch btn btn-xs btn-warning" >切换到缓存</button>
        </div>

        <div class="schedule-info panel-body">

            <table  class=" table table-bordered " style="width: 80%;">
            <tbody>
            <?php $weekday = json_decode($val['worker_schedule_timeline'],1)?>
            <?php foreach ($weekday as $w_key=>$w_val) {
            ?>
                <tr>
                    <th scope="row" weekday="<?= $w_key?>"><?= WorkerSchedule::getWeekdayShow($w_key)?></th>
                    <td class="<?php if(in_array('8:00',$w_val)){echo 'success';}?>">8:00</td>
                    <td class="<?php if(in_array('9:00',$w_val)){echo 'success';}?>">9:00</td>
                    <td class="<?php if(in_array('10:00',$w_val)){echo 'success';}?>">10:00</td>
                    <td class="<?php if(in_array('11:00',$w_val)){echo 'success';}?>">11:00</td>
                    <td class="<?php if(in_array('12:00',$w_val)){echo 'success';}?>">12:00</td>
                    <td class="<?php if(in_array('13:00',$w_val)){echo 'success';}?>">13:00</td>
                    <td class="<?php if(in_array('14:00',$w_val)){echo 'success';}?>">14:00</td>
                    <td class="<?php if(in_array('15:00',$w_val)){echo 'success';}?>">15:00</td>
                    <td class="<?php if(in_array('16:00',$w_val)){echo 'success';}?>">16:00</td>
                    <td class="<?php if(in_array('17:00',$w_val)){echo 'success';}?>">17:00</td>
                    <td class="<?php if(in_array('18:00',$w_val)){echo 'success';}?>">18:00</td>
                    <td class="<?php if(in_array('19:00',$w_val)){echo 'success';}?>">19:00</td>
                    <td class="<?php if(in_array('20:00',$w_val)){echo 'success';}?>">20:00</td>
                    <td class="<?php if(in_array('21:00',$w_val)){echo 'success';}?>">21:00</td>
                    <td class="<?php if(in_array('22:00',$w_val)){echo 'success';}?>">22:00</td>
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
        <div class="schedule-info-redis panel-body" style="display: none">

            <table  class=" table table-bordered " style="width: 77%;" "="">
            <tbody>
            <?php
                $schedule_from_redis = \yii\helpers\ArrayHelper::index($schedule_from_redis,'schedule_id');
                if(isset($schedule_from_redis[$val['id']])){
                    $weekday = $schedule_from_redis[$val['id']]['worker_schedule_timeline'];
                    $weekdayIsDisabled = false;
                }else{
                    $weekday = [1=>[],2=>[],3=>[],4=>[],5=>[],6=>[],7=>[]];
                    $weekdayIsDisabled = true;
                }

            ?>
            <?php foreach ($weekday as $w_key=>$w_val) {
                ?>
                <tr style="height: 36px" class="show-schedule-for-redis">
                    <th scope="row" weekday="<?= $w_key?>"><?= WorkerSchedule::getWeekdayShow($w_key)?></th>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('8:00',$w_val)){echo 'success';}}?>">8:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('9:00',$w_val)){echo 'success';}}?>">9:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('10:00',$w_val)){echo 'success';}}?>">10:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('11:00',$w_val)){echo 'success';}}?>">11:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('12:00',$w_val)){echo 'success';}}?>">12:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('13:00',$w_val)){echo 'success';}}?>">13:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('14:00',$w_val)){echo 'success';}}?>">14:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('15:00',$w_val)){echo 'success';}}?>">15:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('16:00',$w_val)){echo 'success';}}?>">16:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('17:00',$w_val)){echo 'success';}}?>">17:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('18:00',$w_val)){echo 'success';}}?>">18:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('19:00',$w_val)){echo 'success';}}?>">19:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('20:00',$w_val)){echo 'success';}}?>">20:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('21:00',$w_val)){echo 'success';}}?>">21:00</td>
                    <td class="<?php if($weekdayIsDisabled){echo 'disabled';}else{if(in_array('22:00',$w_val)){echo 'success';}}?>">22:00</td>
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
        <td class="success">8:00</td>
        <td class="success">9:00</td>
        <td class="success">10:00</td>
        <td class="success">11:00</td>
        <td class="success">12:00</td>
        <td class="success">13:00</td>
        <td class="success">14:00</td>
        <td class="success">15:00</td>
        <td class="success">16:00</td>
        <td class="success">17:00</td>
        <td class="success">18:00</td>
        <td class="success">19:00</td>
        <td class="success">20:00</td>
        <td class="success">21:00</td>
        <td class="success">22:00</td>
        <th>
            <input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="...">
        </th>
    </tr>
    <tr>
        <th scope="row" weekday="2">周二</th>
        <td class="success">8:00</td>
        <td class="success">9:00</td>
        <td class="success">10:00</td>
        <td class="success">11:00</td>
        <td class="success">12:00</td>
        <td class="success">13:00</td>
        <td class="success">14:00</td>
        <td class="success">15:00</td>
        <td class="success">16:00</td>
        <td class="success">17:00</td>
        <td class="success">18:00</td>
        <td class="success">19:00</td>
        <td class="success">20:00</td>
        <td class="success">21:00</td>
        <td class="success">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="3">周三</th>
        <td class="success">8:00</td>
        <td class="success">9:00</td>
        <td class="success">10:00</td>
        <td class="success">11:00</td>
        <td class="success">12:00</td>
        <td class="success">13:00</td>
        <td class="success">14:00</td>
        <td class="success">15:00</td>
        <td class="success">16:00</td>
        <td class="success">17:00</td>
        <td class="success">18:00</td>
        <td class="success">19:00</td>
        <td class="success">20:00</td>
        <td class="success">21:00</td>
        <td class="success">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="4">周四</th>
        <td class="success">8:00</td>
        <td class="success">9:00</td>
        <td class="success">10:00</td>
        <td class="success">11:00</td>
        <td class="success">12:00</td>
        <td class="success">13:00</td>
        <td class="success">14:00</td>
        <td class="success">15:00</td>
        <td class="success">16:00</td>
        <td class="success">17:00</td>
        <td class="success">18:00</td>
        <td class="success">19:00</td>
        <td class="success">20:00</td>
        <td class="success">21:00</td>
        <td class="success">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="5">周五</th>
        <td  class="success">8:00</td>
        <td  class="success">9:00</td>
        <td  class="success">10:00</td>
        <td  class="success">11:00</td>
        <td  class="success">12:00</td>
        <td  class="success">13:00</td>
        <td  class="success">14:00</td>
        <td  class="success">15:00</td>
        <td  class="success">16:00</td>
        <td  class="success">17:00</td>
        <td  class="success">18:00</td>
        <td  class="success">19:00</td>
        <td  class="success">20:00</td>
        <td  class="success">21:00</td>
        <td  class="success">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="6">周六</th>
        <td  class="success">8:00</td>
        <td  class="success">9:00</td>
        <td  class="success">10:00</td>
        <td  class="success">11:00</td>
        <td  class="success">12:00</td>
        <td  class="success">13:00</td>
        <td  class="success">14:00</td>
        <td  class="success">15:00</td>
        <td  class="success">16:00</td>
        <td  class="success">17:00</td>
        <td  class="success">18:00</td>
        <td  class="success">19:00</td>
        <td  class="success">20:00</td>
        <td  class="success">21:00</td>
        <td  class="success">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    <tr>
        <th scope="row" weekday="7">周日</th>
        <td  class="success">8:00</td>
        <td  class="success">9:00</td>
        <td  class="success">10:00</td>
        <td  class="success">11:00</td>
        <td  class="success">12:00</td>
        <td  class="success">13:00</td>
        <td  class="success">14:00</td>
        <td  class="success">15:00</td>
        <td  class="success">16:00</td>
        <td  class="success">17:00</td>
        <td  class="success">18:00</td>
        <td  class="success">19:00</td>
        <td  class="success">20:00</td>
        <td  class="success">21:00</td>
        <td  class="success">22:00</td>
        <th><input type="checkbox" id="blankCheckbox" checked value="option1" aria-label="..."></th>
    </tr>
    </tbody>
    </table>
</div>
</div>
