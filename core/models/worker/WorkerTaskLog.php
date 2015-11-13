<?php

namespace core\models\worker;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use core\models\finance\FinanceWorkerNonOrderIncomeSearch;
use core\models\worker\WorkerTask;

/**
 * This is the model class for table "{{%worker_task_log}}".
 *
 * @property integer $id
 * @property integer $worker_id
 * @property integer $worker_task_id
 * @property string $worker_task_name
 * @property integer $worker_task_start
 * @property integer $worker_task_end
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_del
 */
class WorkerTaskLog extends \dbbase\models\worker\WorkerTaskLog
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['worker_task_reward_value'], 'match', 'pattern'=>'/^[0-9]+(.[0-9]{1,2})?$/']
        ]);
    }
    /**
     * 获取每个条件对应的所有数值
     */
    public function getConditionsValues()
    {
        $models = (array)WorkerTaskLogmeta::find()
        ->select(['worker_tasklog_condition', 'worker_tasklog_value'])
        ->where(['worker_tasklog_id'=>$this->id])
        ->asArray()->all();
        return $models;
    }
    /**
     * 计算是否完成
     * 调用WorkerTask类里的方法判断完成
     */
    public function calculateIsDone()
    {
        if($this->worker_task_is_done==1){
            return true;
        }
        $task = WorkerTask::findOne($this->worker_task_id);
        $is_done = $task->calculateValuesIsDone($this->getConditionsValues());
        if($is_done){
            $this->worker_task_is_done = 1;
            $this->worker_task_done_time = time();
        }elseif($task->worker_task_end<time()){//如果结束时间小于当前时间，则永远为未完成 
            $this->worker_task_is_done = -1;
        }
        $this->save();
        return $is_done;
    }
    /**
     * 存数值
     * @param array $metas. eg: ['1'=>5,2=>6]
     */
    public function setValues($metas)
    {
        $data = [];
        foreach ($metas as $condition=>$value){
            WorkerTaskLogmeta::deleteAll([
                'worker_task_id'=>$this->worker_task_id,
                'worker_tasklog_id'=>$this->id,
                'worker_id'=>$this->worker_id,
                'worker_tasklog_condition'=>$condition,
            ]);
            $_meta = new WorkerTaskLogmeta();
            $_meta->setAttributes([
                'worker_task_id'=>$this->worker_task_id,
                'worker_tasklog_id'=>$this->id,
                'worker_id'=>$this->worker_id,
                'worker_tasklog_condition'=>$condition,
            ]);
            $_meta->worker_tasklog_value = $value;
            $_meta->save();
            $data[] = $_meta;
        }
        return $data;
    }
    /**
     * 任务描述
     */
    public function getWorker_task_description()
    {
        $model = WorkerTask::findOne(['id'=>$this->worker_task_id]);
        return $model->worker_task_description;
    }
    /**
     * 任务详情
     * @return unknown
     * eg: WorkerTaskLog::findOne(['id'=>$id])->getDetail();
     * 
     */
    public function getDetail()
    {
        $model = self::findOne(['id'=>$this->id]);
        $data = $model->attributes;
        $data['values'] = $model->getConditionsValues();
        $data['worker_task_description'] = $model->getWorker_task_description();
        $worker_task = WorkerTask::findOne($this->worker_task_id);
        $cons= $worker_task->getConditions();
        $data['worker_task_description_url'] = $worker_task->worker_task_description_url;
        $_values = [];
        foreach ($cons as $key=>$con){
            foreach ($data['values'] as $value){
                if($con['id']==$value['worker_tasklog_condition']){
                    $_values[] = array_merge($con, $value);
                }
            }
        }
        $data['values'] = $_values;
//        foreach($data['values'] as $k1=>$v1){
//            foreach($cons as $k2=>$v2){
//                if($v1["worker_tasklog_condition"]==$v2['id']){
//                    $data['values']["$k1"]['name']=$v2["name"];
//                    $data['values']["$k1"]['judge']=$v2["judge"];
//                    $data['values']["$k1"]['value']=$v2["value"];
//                }else{
////                    unset($data['values']["$k1"]);
//                }
//            }
//        }
        
        return $data;
    }
    /**
     * 判断并设置结算与否
     * @return boolean
     */
    public function setSettlemented()
    {
        $is_sl = FinanceWorkerNonOrderIncomeSearch::isWorkerTaskSettled($this->id);
        $this->worker_task_is_settlemented = $is_sl;
        return $this->save();
    }
    /**
     * 当前阿姨任务列表
     */
    public static function getCurListByWorkerId($worker_id)
    {
        $time = time();
        return self::find()->where([
            'worker_id'=>$worker_id
        ])->andFilterWhere(['<=', 'worker_task_log_start', $time])
        ->andFilterWhere(['>', 'worker_task_log_end', $time])
        ->all();
    }
    /**
     * 获取已完成的任务
     * @param int $worker_id
     * @param int $is_doned 是否完成.取值范围(-1:任务失败,0:未处理,1:任务成功完成)
     * @param int $page
     * @param int $page_size
     */
    public static function getDonedTasks($worker_id, $is_doned, $page=1,$page_size=20)
    {
        $offset = ($page-1)*$page_size;
        $models = self::find()
        ->andFilterWhere(['=', 'worker_id', $worker_id])
        ->andFilterWhere(['=', 'worker_task_is_done', $is_doned])
        ->offset($offset)
        ->limit($page_size)
        ->all();
        $data = [];
        foreach ($models as $key=>$model){
            $data[$key] = $model->attributes;
            $data[$key]['values'] = $model->getConditionsValues();
            $data[$key]['worker_task_description'] = $model->getWorker_task_description();
        }
        return $data;
    }
}
