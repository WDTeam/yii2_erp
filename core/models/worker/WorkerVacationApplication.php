<?php

namespace core\models\worker;

use Yii;
use core\models\worker\WorkerVacation;

/**
 * This is the model class for table "{{%worker_vacation_application}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_vacation_application_start_time
 * @property integer $worker_vacation_application_end_time
 * @property integer $worker_vacation_application_approve_status
 * @property integer $worker_vacation_application_approve_time
 * @property integer $created_ad
 */
class WorkerVacationApplication extends \dbbase\models\worker\WorkerVacationApplication
{
    /**
     * 获取阿姨请假排班表
     * @param $worker_id
     * @param $vacationType 阿姨请假类型 1休假 2事假
     * @return array
     */
    public static function getApplicationTimeLine($worker_id,$vacationType){
        $weekArr = self::getWorkerVacationWeekArr($worker_id,$vacationType);

        $noWeekday = [0,6,5];
        $timeLine = [];
        for($i=0;$i<14;$i++){
            $time = strtotime("+$i day");
            $date = date('Y-m-d',$time);
            $week = (int)date("W",$time);
            $isEnable = true;
            if($vacationType==1){
                //本周是否已请休假
                if(in_array($week,$weekArr)){
                    $isEnable = false;
                }
                //周5,6,7 不可请假
                $weekday = (int)date("w",$time);
                if(in_array($weekday,$noWeekday)){
                    $isEnable = false;
                }
            }else{
                //如果本周请了事假,则连续两周都不能请事假
                if($week%2==1){
                    if(in_array($week,$weekArr) || in_array($week+1,$weekArr)){
                        $isEnable = false;
                    }
                }else{
                    if(in_array($week,$weekArr) || in_array($week-1,$weekArr)){
                        $isEnable = false;
                    }
                }

            }


            //阿姨是否已预约出去

            $timeLine[] = [
                'date'=>$date,
                'enable'=>$isEnable
            ];
        }

        return $timeLine;
    }

    protected static function getWorkerVacationWeekArr($worker_id,$vacationType){
        $nowTime = strtotime(date('Y-m-d'));
        //类型为休假 返回2周阿姨请假信息
        if($vacationType==1){
            $startTime = $nowTime;
            $endTime = strtotime('+14 day',$nowTime);
        //类型为事假 返回4周阿姨请假信息
        }else{
            $startTime = strtotime('-7 day',$nowTime);
            $endTime = strtotime('+21 day',$nowTime);
        }

        $condition = "(worker_vacation_start_time > $startTime and worker_vacation_start_time < $endTime) or (worker_vacation_finish_time <$startTime and worker_vacation_finish_time >$endTime )";
        $vacationTimeResult = WorkerVacation::find()->select('worker_vacation_start_time,worker_vacation_finish_time')->AndWhere(['worker_id'=>$worker_id])->AndWhere(['worker_vacation_type'=>$vacationType])->AndWhere($condition)->asArray()->all();
        $weekArr = [];
        foreach($vacationTimeResult as $val){
            $startWeek = (int)date('W',$val['worker_vacation_start_time']);
            $endWeek = (int)date('W',$val['worker_vacation_finish_time']);
            $weekArr[] = $startWeek;
            for($i=1;$i<=$endWeek-$startWeek;$i++){
                $weekArr[] = $startWeek+$i;
            }
        }
        return $weekArr;
    }

    /**
     * 获取阿姨请假申请列表
     * @param $worker_id 阿姨id
     * @param int $page 每页的页码
     * @param int $pageNum 返回条数
     * @return array
     */
    public static function getApplicationList($worker_id,$page=1,$pageNum=10){
        $start = ($page-1)*$pageNum;
        $result = self::find()->where(['worker_id'=>$worker_id])->offset($start)->limit($pageNum)->orderBy('id desc')->asArray()->all();
        $data = ['page'=>$page,'pageNum'=>$pageNum,'data'=>$result];
        return $data;
    }

    /**
     * 检查阿姨是否请假
     * @param $worker_id 阿姨id
     * @param $vacationDate 阿姨请假日期
     * @param $vacationType 阿姨请假类型 1休假 2事假
     * @return bool true 可用 false 不可用
     * @return array
     */
    public static function checkWorkerIsApplication($worker_id,$vacationDate,$vacationType){
        $condition['worker_vacation_application_start_time'] = strtotime($vacationDate);
        $condition['worker_id'] = $worker_id;
        $condition['worker_vacation_application_type'] = $vacationType;
        $condition['worker_vacation_application_approve_status'] = [0,1];
        $result = self::find()->where($condition)->asArray()->one();
        if($result){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 申请请假阿姨信息
     * @param $worker_id 阿姨id
     * @param $vacationDate 阿姨请假日期
     * @param $vacationType 阿姨请假类型 1休假 2事假
     * @return bool true 创建成功 false创建失败
     */
    public static function createVacationApplication($worker_id,$vacationDate,$vacationType){
        if($worker_id && $vacationDate && $vacationType){
            $model = new WorkerVacationApplication();
            $model->worker_id = $worker_id;
            $model->worker_vacation_application_type = $vacationType;
            $model->worker_vacation_application_start_time = strtotime($vacationDate);
            $model->worker_vacation_application_end_time = strtotime($vacationDate);
            $model->worker_vacation_application_approve_status = 0;
            $model->created_ad = time();
            return $model->save();
        }else{
            return fasle;
        }
    }

    public function getWorker(){
        return $this->hasOne(Worker::className(),['id'=>'worker_id']);
    }
}
