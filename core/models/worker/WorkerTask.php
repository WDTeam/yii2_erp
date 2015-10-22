<?php
namespace core\models\worker;

use yii\base\InvalidParamException;
use core\models\Operation\CoreOperationCity;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
class WorkerTask extends \common\models\WorkerTask
{
    /**
     * 条件名
     */
    const CONDITION_NAME = [
        1=>'取消订单 ',
        2=>'拒绝订单',
        3=>'服务老用户',
        4=>'主动接单',
        5=>'完成工时',
//         6=>'完成小保养个数 ',
    ];
    /**
     * 条件判断符
     */
    const CONDITION_JUDGE = [
        '<'=>'小于',
        '<='=>'小于等于',
        '='=>'等于',
        '>='=>'大于等于',
        '>'=>'大于',
        '<>'=>'不等于'
    ];

    /**
     * 任务奖励类型
     */
    const REWARD_TYPES = [
        1=>'现金(元)',
        2=>'当月流量(MB)',
        3=>'次月流量(MB)',
    ];
    
    const TASK_CYCLES = [
        1=>'月',
        2=>'周',
        3=>'天'
    ];
    
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
            [['worker_task_name', 'worker_task_start', 'worker_task_end', 'worker_task_reward_type'], 'required'],
            [['worker_types', 'worker_rules', 'worker_cites'], 'safe'],
            [['conditions'], 'validateConditions'],
        ]);
    }
    
    public function validateConditions($attribute, $params)
    {
//         if (!$this->hasErrors()) {
//             $this->addError($attribute, \Yii::t('app', '条件错误.'));
//         }
    }
    
    public function getConditions()
    {
        $names = self::CONDITION_NAME;
        $data = (array)json_decode($this->worker_task_conditions, true);
        foreach ($data as $item){
            if(isset($names[$item['id']]) && isset($item['value']) && $item['value']!=''){
                $data[$item['id']]['name'] = $names[$item['id']];
            }else{
                unset($data[$item['id']]);
            }
        }
        return $data;
    }
    public function setConditions($data)
    {
        $this->worker_task_conditions = json_encode($data);
    }
    /**
     * 阿姨类型字段
     */
    public function getWorker_types()
    {
        return explode(',', $this->worker_type);
    }
    public function setWorker_types($value)
    {
        $this->worker_type = implode(',', $value);
    }
    /**
     * 阿姨角色字段
     */
    public function getWorker_rules()
    {
        return explode(',', $this->worker_rule_id);
    }
    public function setWorker_rules($value)
    {
        $this->worker_rule_id = implode(',', $value);
    }
    /**
     * 城市字段
     */
    public function getWorker_cites()
    {
        return explode(',', $this->worker_task_city_id);
    }
    public function setWorker_cites($value)
    {
        $this->worker_task_city_id = implode(',', $value);
    }
    /**
     * 完整条件，包含所有已设置和未设置的
     */
    public function getFullConditions()
    {
        $res = [];
        $cons = $this->getConditions();
        $names = self::CONDITION_NAME;
        foreach ($names as $id=>$name){
            $res[$id] = [
                'id'=>$id,
                'name'=>$name,
                'judge'=>'',
                'value'=>'',
            ];
            if(isset($cons[$id])){
                $res[$id] = array_merge($res[$id],[
                    'judge'=>$cons[$id]['judge'],
                    'value'=>$cons[$id]['value'],
                ]);
            }
        }
        return $res;
    }
    
    /**
     * 显示已选的角色类型
     */
    public function getWorkerTypeLabels()
    {
        $types = Worker::getWorkerTypeList();
        $cur_typeids = $this->getWorker_types();
        $res = [];
        foreach ($cur_typeids as $id){
            if(isset($types[$id])){
                $res[] = $types[$id];
            }
        }
        return implode(', ', $res);
    }
    /**
     * 显示已选的身份类型
     */
    public function getWorkerRuleLabels()
    {
        $types = WorkerRuleConfig::getWorkerRuleList();
        $cur_ruleids = $this->getWorker_rules();
        $res = [];
        foreach ($cur_ruleids as $id){
            if(isset($types[$id])){
                $res[] = $types[$id];
            }
        }
        return implode(', ', $res);
    }
    
    /**
     * 显示已选的城市
     */
    public function getWorkerCityLabels()
    {
        $types = WorkerTask::getOnlineCites();
        $cur_cityids = $this->getWorker_cites();
        $res = [];
        foreach ($cur_cityids as $id){
            if(isset($types[$id])){
                $res[] = $types[$id];
            }
        }
        return implode(', ', $res);
    }
    /**
     * 周期显示
     */
    public function getCycleLabel()
    {
        $cycles = self::TASK_CYCLES;
        return $cycles[$this->worker_task_cycle];
    }
    
    
    /**
     * 计算符合阿姨条件的任务列表
     */
    public static function getTaskListByWorkerId($worker_id)
    {
        $cur_time = time();
        $worker = Worker::findOne(['id'=>$worker_id]);
        if(empty($worker)){
            throw new InvalidParamException('阿姨不存在');
        }
        $tasks = self::find()
        ->where("FIND_IN_SET({$worker->worker_type}, worker_type) 
            AND FIND_IN_SET({$worker->worker_rule_id}, worker_rule_id) 
            AND worker_task_online=1")
        ->andFilterWhere(['<','worker_task_start', $cur_time])
        ->andFilterWhere(['>','worker_task_end', $cur_time])
        ->all();
        return $tasks;
    }
    /**
     * 获取周期开始和结束时间
     * @param int $cycle 周期单位 1:月，2：周，3：天
     */
    public static function getCycleTimes($cycle)
    {
        if($cycle==1){
            $start = mktime(0,0,0,date('m'),1,date('Y'));
            $end = mktime(23,59,59,date('m'),date('t'),date('Y'));
            $cycle_number = 'm'.date('Ym');
        }elseif ($cycle==2){
            $start = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
            $end = mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));
            $cycle_number = 'w'.date('YW');
        }elseif($cycle==3){
            $start = mktime(0,0,0,date('m'),date('d'),date('Y'));
            $end = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            $cycle_number = 'd'.date('Ymd');
        }
        return [$cycle_number, $start, $cycle_number];
    }
    /**
     * 自动生成阿姨任务
     */
    public static function autoCreateTaskLog($worker_id)
    {
        $data = [];
        $tasks = (array)self::getTaskListByWorkerId($worker_id);
        foreach($tasks as $task){
            $cycletime = self::getCycleTimes($task->worker_task_cycle);
            $log = WorkerTaskLog::find()->where([
                'worker_id'=>$worker_id,
                'worker_task_id'=>$task->id,
                'worker_task_cycle_number'=>$cycletime[0],
            ])->one();
            if(empty($log)){
                $log = new WorkerTaskLog();
            }
            $log->setAttributes([
                'worker_id'=>$worker_id,
                'worker_task_id'=>$task->id,
                'worker_task_cycle_number'=>$cycletime[0],
                'worker_task_name'=>$task->worker_task_name,
                'worker_task_log_start'=>$cycletime[1],
                'worker_task_log_end'=>$cycletime[2],
                'worker_task_reward_type'=>$task->worker_task_reward_type,
                'worker_task_reward_value'=>$task->worker_task_reward_value,
            ]);
            $log->save();
            $data[] = $log;
        }
        return $data;
    }
    /**
     * 给定数据判断是否完成
     * @param array $tasklogmetas 数值记录
     */
    public function calculateValuesIsDone($tasklogmetas)
    {
        $isfalse = 0;
        $cons = $this->getConditions();
        foreach($cons as $con){
            foreach ($tasklogmetas as $meta){
                if($con['id']==$meta['worker_tasklog_condition']){
                    $is_done = eval($meta['worker_tasklog_value'].$con['judge'].$con['value']);
                    if($is_done==false){
                        $isfalse++;
                    }
                }
            }
        }
        return $isfalse<=0;
    }
    /**
     * 开通的城市列表
     */
    public static function getOnlineCites()
    {
        $cites = CoreOperationCity::getCityOnlineInfoList();
        return ArrayHelper::map($cites, 'city_id', 'city_name');
    }
    /**
     * 指定时间内阿姨已完成的任务记录列表,用于结算
     * 而且是金钱奖励
     * eg:$log = WorkerTask::getDoneTasksByWorkerId(1441036800, 1443627800, 12);
     * var_dump($log);exit;
     */
    public static function getDoneTasksByWorkerId($start_time, $end_time, $worker_id)
    {
        $models = WorkerTaskLog::find()->where([
            'worker_id'=>$worker_id,
            'worker_task_is_done'=>1,
            'worker_task_reward_type'=>1
        ])
        ->filterWhere(['>=','worker_task_done_time', $start_time])
        ->filterWhere(['<','worker_task_done_time', $end_time])
        ->all();
        return $models;
    }
}